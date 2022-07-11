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


class TClass():
    # name = "books"
    # allowed_domains = ["finance.naver.com"]
    # start_urls = [
    #     'https://finance.naver.com/sise/sise_market_sum.naver',
    # ]
    domain = ""
    depthurl = ""

    def __init__(self, domain=""):
        self.domain = domain

    def req(self, url, callback=None):
        splash_url = 'http://host.docker.internal:8050/render.html?'
        encoded = urlencode({"url": url, "html": 1, "timeout": 10, "wait": 1})
        response = requests.get(f'{splash_url}{encoded}')

        content_type = response.headers['content-type']
        if not 'charset' in content_type:
            response.encoding = response.apparent_encoding
        html = response.text
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
            start = int(data["startPage"])
            end = data["endPage"]
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
                for y in self.parse(self.req(finurl), data):
                    returnObj.append(y)
                    # yield y
                yield returnObj
                if cnt > end:
                    break
            else:
                for y in self.parse(self.req(finurl), data):
                    yield y
                break

    def parse(self, response, data=None):
        for _data in data['queries']:
            query = _data['query']
            column = _data['column']
            dtype = _data['type']
            results = []

            for result in response.select(query):
                if dtype == "text":
                    results.append(result.get_text(strip=True))
                elif dtype == "page":
                    txt = result.get_text()
                    href = result.get('href')
                    if "child" in _data:
                        for __data in self.subparse(self.req(self.urljoin(href)), _data['child']):
                            results.append(__data)
                    else:
                        results.append(result.get_text(
                            strip=True)+"|"+result.get("href"))
            yield {"column": column, "query": query, "data": results}

    def subparse(self, response, data=None):
        results = {}
        for _data in data['queries']:
            query = _data['query']
            column = _data['column']
            dtype = _data['type']
            results[column] = []
            for result in response.select(query):
                results[column].append(result.get_text(strip=True))
        yield results


with open("/data/test.json", 'r', encoding='utf-8') as f:
    ori_data = json.load(f)

# mainurl = "https://finance.naver.com/sise/sise_market_sum.naver"
# encoded = urlencode({"url":mainurl,"html":1,"timeout":10,"wait":1})

# url = f'http://host.docker.internal:8050/render.html?{encoded}'

# _main(ori_data[maincnt])

# print(maindata)
domain = ori_data['domain']
data = ori_data['data']
f = TClass(ori_data['domain'])

cnt = 0
final_data = []


# mainurl = "https://finance.naver.com/item/frgn.naver?code=005930&page=1"
# encoded = urlencode({"url": mainurl, "html": 1, "timeout": 10, "wait": 1})

# url = f'http://host.docker.internal:8050/render.html?{encoded}'


# if final_data.get(f"page{cnt+1}") is None:
for _data in data:
    if "url" in _data:
        for z in f.parseMain(_data, cnt):
            final_data.append(z)

        # final_data.append({})
        # for z in f.parseMain(_data, cnt):
        #     isName = isName if z.get("name") is None else z.get("name")
        #     if z.get("name") is not None:
        #         final_data[cnt][z.get("name")] = {}
        #         final_data[cnt][z.get("name")]['type'] = z.get("type")
        #         final_data[cnt][z.get("name")]['data'] = []
        #     else:
        #         final_data[cnt][isName]['data'].append(z)
        # cnt = cnt+1

        # print(final_data)
        # f.parseMain(data[0])
    else:
        continue

with open("new_json7.json", "w", encoding="UTF-8") as f:
    json.dump(final_data, f, ensure_ascii=False, indent=4)
    print("New JSON file is created!")
