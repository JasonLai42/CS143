# set up SparkContext for BookPairs application
from itertools import combinations
from pyspark import SparkContext
sc = SparkContext("local", "BookPairs")

# the main map-reduce task
lines = sc.textFile("/home/cs143/data/goodreads.user.books")
books = lines.map(lambda line: line.split(":")[1])
bookLists = books.map(lambda book: book.split(","))
userBookPairs = bookLists.flatMap(lambda bookList: combinations(bookList, 2))
bookPairs1 = userBookPairs.map(lambda userBookPair: ((int(sorted(userBookPair)[0]), int(sorted(userBookPair)[1])), 1))
allBookPairs = bookPairs1.reduceByKey(lambda a, b: a + b)
bookPairs = allBookPairs.filter(lambda bookPair: bookPair[1] > 20)
bookPairs.saveAsTextFile("output")
