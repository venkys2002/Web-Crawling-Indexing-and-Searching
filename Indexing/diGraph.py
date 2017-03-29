import networkx as nx
import matplotlib.pyplot as plt
import csv

csv_reader = csv.reader(open('/Users/abhinavkumar/Desktop/IR_Assignment/Assignment3/pageRank.csv'))

G = nx.DiGraph()

for row in csv_reader:
    var1 = row[0]
    src = str(var1).rstrip("/")
    G.add_node(src)
    for column in range(1,len(row)):
        var2 = row[column]
        dest = str(var2).rstrip("/")
        G.add_node(dest)
        #G.add_nodes_from([var1,row[column]])
        G.add_edge(src,dest)

pr = nx.pagerank(G, alpha=0.85)
print len(pr)

text_file = open("/Users/abhinavkumar/Desktop/IR_Assignment/Assignment3/external_pageRankFile.txt", "w")
for name in pr:
    name1 = str(name).rstrip("/")
    name2 = str(name1).replace("/","--")
    text_file.write(str("/Users/abhinavkumar/solr-5.3.1/../Desktop/WebCrawling/Output/downloads/"+name2))
    text_file.write(str("="))
    text_file.write(str(pr[name]))
    text_file.write(str("\n"))

text_file.close()
"""
 # draw graph
pos = nx.shell_layout(G)
nx.draw(G, pos)

# show graph
plt.show()
"""