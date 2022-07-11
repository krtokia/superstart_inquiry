#-*- coding:utf-8 -*-
import sys
import io
sys.stdout = io.TextIOWrapper(sys.stdout.detach(), encoding = 'utf-8')
sys.stderr = io.TextIOWrapper(sys.stderr.detach(), encoding = 'utf-8')
import logging

import requests
import json
import re
import collections
from bs4 import BeautifulSoup
from urllib.parse import unquote, quote, quote_plus, urlencode

with open("/data/test.json", 'r', encoding='utf-8') as f:
    ori_data = json.load(f)

for x in ori_data['data'][0]['queries']:
    if 'child' in x:
        print("hello")
    else:
        print("hell")