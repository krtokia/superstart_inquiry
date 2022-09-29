from pythonRender import pyrender2

import log

consoleLog = log.get_logger("parse")

url = ""
html = pyrender2(url,consoleLog)

consoleLog.error(html['statusCode'])