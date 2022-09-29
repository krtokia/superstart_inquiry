# -*- coding:utf-8 -*-
from urllib.parse import unquote, quote, quote_plus, urlencode
from bs4 import BeautifulSoup
import collections
import re
import json
import requests
import logging
import sys
import io
sys.stdout = io.TextIOWrapper(sys.stdout.detach(), encoding='utf-8')
sys.stderr = io.TextIOWrapper(sys.stderr.detach(), encoding='utf-8')
import pymysql

global pKey
pKey = sys.argv[1]
del sys.argv[1]

from pythonRender import pyrender
import log

import traceback

class TClass():
    domain = ""
    depthurl = ""
    page = ""
    consoleLog = log.get_logger("parse")
    pageStart = -1
    pageEnd = -1

    def __init__(self, domain="", pageStart=-1, pageEnd=-1):
        self.domain = domain
        self.pageStart = pageStart
        self.pageEnd = pageEnd
        self.consoleLog.info("start "+pKey)

    def req(self, url, callback=None):
        # splash_url = 'http://host.docker.internal:8050/render.html?'
        # encoded = urlencode({"url": url, "html": 1, "timeout": 10, "wait": 1})
        # response = requests.get(f'{splash_url}{encoded}')

        # content_type = response.headers['content-type']
        # if not 'charset' in content_type:
        #     response.encoding = response.apparent_encoding
        # html = response.text
        
        self.consoleLog.info("call pKey:"+pKey+" ["+url+"]")
        html = pyrender(url,self.consoleLog)
        soup = BeautifulSoup(html, 'html.parser')

        return soup

    def urljoin(self, url, first=False):
        if url is None:
            return ""
        url = re.sub('\/$', '', url)
        if re.match(r'^\/', url):
            self.depthurl = ""
            rev = url
        elif re.match(r'^https?\:\/\/', url):
            self.depthurl = ""
            rev = re.sub('^https?\:\/\/[^\/]+\/', f'{self.depthurl}/', url)
        else:
            rev = re.sub('^', f'{self.depthurl}/', url)
            self.depthurl = ""

        if first:
            rev = re.sub('^https?\:\/\/[^\/]+', '', rev)

        fullurl = self.domain + self.depthurl + rev
        self.depthurl = re.sub('/[^/]+$', '', rev)

        return fullurl

    def parseMain(self, data, num=0):

        url = self.urljoin(data['url'])
        isList = url.find("[[:page:]]")
        urls = []
        if isList > -1:
            start = self.pageStart
            end = self.pageEnd
            try:
                end = int(end)
            except ValueError as e:
                end = -1
            cnt = start
        else:
            start = -1
            cnt = -1
            end = -1
        while True:
            if cnt > -1:
                finurl = url.replace("[[:page:]]", str(cnt))
            else:
                finurl = url
            cnt = cnt+1
            returnObj = []
            if end > -1:
                # yield finurl
                for y in self.parse(self.req(finurl), data, finurl):
                    yield y
                    # returnObj.append(y)
                # yield returnObj
                if cnt > end:
                    break
            else:
                for y in self.parse(self.req(finurl), data, finurl):
                    yield y
                break
        self.consoleLog.info("end "+pKey)

            

    def parse(self, response, data=None, finurl=""):
        for _data in data['queries']:
            query = _data['query']
            column = _data['column']
            dtype = _data['type']
            results = []
            
            datatype = re.sub('[SM]$', '', dtype)
            smtype = re.sub('^([a-z]+)(S|M)$', r'\2', dtype)
            for result in response.select(query):
                if datatype == "text":
                    results.append(result.get_text(strip=True))
                elif datatype == "child":
                    txt = result.get_text()
                    href = result.get('href')
                    if "child" in _data:
                        for __data in self.subparse(self.req(self.urljoin(href)), _data['child']):
                            results.append(__data)
                            if smtype == "S":
                                break
                    else:
                        results.append(result.get_text(strip=True)+"|"+result.get("href"))
                elif datatype == "href":
                    results.append(result.get_text(strip=True)+"|"+result.get("href"))
                if smtype == "S":
                    break
            yield {"column": column, "query": query, "data": results, "url": finurl}

    def subparse(self, response, data=None, finurl=""):
        results = {}
        for _data in data['queries']:
            query = _data['query']
            column = _data['column']
            dtype = _data['type']
            results[column] = []
            for result in response.select(query):
                results[column].append(result.get_text(strip=True))
        yield results




con = pymysql.connect(host='host.docker.internal', user='ss_inquiry', password='2017tbtm!1004',
                       db='superstart_inquiry', charset='utf8')
# DB Update Execute
# cur = con.cursor()
# sql = """UPDATE queries SET status = 1 WHERE pKey="""+pKey
# cur.execute(sql)
# con.commit()


# DB SELECT Execute
sql = """select * from queries WHERE pKey="""+pKey
cur = con.cursor(pymysql.cursors.DictCursor)
cur.execute(sql)
dbresult = cur.fetchone()

jsquery = dbresult['jsquery']
ori_data = json.loads(jsquery)

ps = dbresult['pageStart']
pe = dbresult['pageEnd']

domain = ori_data['domain']
data = ori_data['data']
f = TClass(ori_data['domain'], ps, pe)

cnt = 0
final_data = []

try:
    for _data in data:
        if "url" in _data:
            for z in f.parseMain(_data, cnt):
                final_data.append(z)
        else:
            continue
except Exception as e:
    consoleLog = log.get_logger("parse")
    trace_back = traceback.format_exc()
    message = str(e)+ "\n" + str(trace_back)
    consoleLog.info("[ERROR] pKey:"+pKey+" "+str(message))
    cur = con.cursor()
    sql = """UPDATE queries SET status = -1, udt = CURRENT_TIMESTAMP() WHERE pKey="""+pKey
    cur.execute(sql)
    con.commit()
    con.close()

filename = dbresult['author']+"_"+str(dbresult['pKey'])+".json"

with open("/var/www/html/python/jsons/"+filename, "w", encoding="UTF-8") as f:
    json.dump(final_data, f, ensure_ascii=False, indent=4)

#DB Update Execute
cur = con.cursor()
sql = """UPDATE queries SET status = 2, udt = CURRENT_TIMESTAMP() WHERE pKey="""+pKey
cur.execute(sql)
con.commit()

con.close()