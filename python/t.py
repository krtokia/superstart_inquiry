# -*- coding: utf-8 -*-
import sys
import io
sys.stdout = io.TextIOWrapper(sys.stdout.detach(), encoding = 'utf-8')
sys.stderr = io.TextIOWrapper(sys.stderr.detach(), encoding = 'utf-8')

import requests
import re
from bs4 import BeautifulSoup

class TClass():
    name = "books"
    allowed_domains = ["finance.naver.com"]
    start_urls = [
        'https://finance.naver.com/sise/sise_market_sum.naver',
    ]
    domain = "https://finance.naver.com"
    depthurl = ""
    
    def req(self, url, callback=None):
        response = requests.get(url)

        content_type = response.headers['content-type']
        if not 'charset' in content_type:
            response.encoding=response.apparent_encoding

        html = response.text
        soup = BeautifulSoup(html, 'html.parser')

        #if callback:
        #    callback(soup)

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
        
        fullurl = self.domain +self.depthurl + rev
        self.depthurl = re.sub('/[^/]+$', '', rev)

        return fullurl
    


     
    def parse(self, response):
        #for book_url in response.select("article.product_pod > div > a"):
        print({"name": "name"})

        for targets in response.select("table.type_2 > tbody > tr > td > a.tltle:nth-child(1)"):
            print(targets)
        """
        try:
            next_page = response.select("li.next > a")[0].get("href")
        except Exception:
            next_page = None
        nextp = self.urljoin(next_page)
        print(nextp)
        if next_page:
            self.parse(self.req(nextp))
        """

    def parse_book_page(self, response):
        print("HELLO")


def ahref(response):
    print("Hello, World!")

f = TClass()

url = f.start_urls[0]

u = f.urljoin(url, first=True)

response = f.req(u)

cnt = 0
f.parse(response)

