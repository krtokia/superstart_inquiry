# -*- coding utf-8 -*-
from openpyxl import Workbook

wb = Workbook()
ws = wb.active

ws.title = "데이터"


for i in range(1, 6):
    for j in range(1, 10):
        ws.cell(row=i, column=j, value=f'{i} , {j} 셀')


wb.save("ex.xlsx")
