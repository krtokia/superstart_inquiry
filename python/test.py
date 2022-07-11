# -*- coding:utf-8 -*-
from urllib.parse import unquote, quote, quote_plus, urlencode
from bs4 import BeautifulSoup, NavigableString
import collections
import re
import json
import requests
import logging
import sys
import io
sys.stdout = io.TextIOWrapper(sys.stdout.detach(), encoding='utf-8')
sys.stderr = io.TextIOWrapper(sys.stderr.detach(), encoding='utf-8')


response = requests.get(
    f'https://finance.naver.com/item/board_read.naver?code=079550&nid=221582158&st=&sw=&page=1')

content_type = response.headers['content-type']
if not 'charset' in content_type:
    response.encoding = response.apparent_encoding
html = response.text
soup = BeautifulSoup(html, 'html.parser')

target = soup.select("table.view")

# print(",".join(target[0].contents))
# text = ""
# for i in target[0].contents:
#     if(isinstance(i, NavigableString)):
#         text += i.replace("\n", "").strip()
#     elif i.name == "br":
#         text += "\n"

print(target)
