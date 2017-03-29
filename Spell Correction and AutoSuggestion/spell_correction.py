from os import listdir
from os.path import isfile, join
import html2text
import codecs


files = [f for f in listdir('/Users/abhinavkumar/Desktop/WebCrawling/Output/downloads/') if isfile(join('/Users/abhinavkumar/Desktop/WebCrawling/Output/downloads/', f))]
out = codecs.open('spell_db.txt', 'ab', 'utf-8')
for file in files:
  try:
    if '.pdf' not in file:
      h = html2text.HTML2Text()
      h.ignore_links = True
      text = h.handle(codecs.open('/Users/abhinavkumar/Desktop/WebCrawling/Output/downloads/'+file, 'rb', 'utf-8').read())
      for token in text.split():
        if len(token) > 2:
          out.write(token+'\n')
  except Exception as e:
    print e
  print file