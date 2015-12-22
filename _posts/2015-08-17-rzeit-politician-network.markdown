---
layout: blog-layout
title: "Constructing a network of politicians from newspaper data"
author: "Jana Blahak, Jan Dix and Simon Munzert"
authorref: "https://twitter.com/simonsaysnothin"
date: 2015-08-17 10:00:00
teaser-pic: "/img/blog/zeit_online.jpg"
category: rstats
blogo: "main"
---


*The following is a guest post by Jana Blahak and Jan Dix (University of Konstanz), with support from Simon Munzert.*

In the [last post](http://www.r-datacollection.com/blog/rzeit-package-intro/), we introduced the
[*rzeit* package](https://github.com/tollpatsch/rzeit), an R binding to the [Content API](http://developer.zeit.de/index/) at [ZEIT Online](http://www.zeit.de/). This time, we give a little demonstration of what can be done with these media data.

The question we ask is the following: Can we use information from newspaper articles to learn about connections between political actors? As actors, we choose members of Angela Merkel's cabinet---ZEIT Online is a German newspaper website, so they are particularly strong in reporting about German politics. We assume that if pairs of ministers are mentioned in the same article, this represents some form of connectivity between those politicians and/or their departments. Given this information, we might even learn about the centrality or importance of particular ministries within the government. To do so, we will use basic tools of network visualization.


## Loading packages

We start with load all required packages, including our `rzeit` package (available from [Github](https://github.com/tollpatsch/rzeit)):


```r
library(rzeit)
library(stringr)
library(jsonlite)
library(lubridate)
library(rvest)
library(plyr)
library(networkD3)
```


## Gathering data

In a first step, we gather information on German ministers from [Wikipedia](https://de.wikipedia.org/wiki/Bundesregierung_%28Deutschland%29), which holds a table on their department, name, and party affiliation. We can do so in no time with handy functions from the `rvest` package:



```r
### parse website
government_url <- "https://de.wikipedia.org/wiki/Bundesregierung_%28Deutschland%29"
government_parsed <- html(government_url, encoding = "UTF8")

### import and tidy table
government_tables <- html_table(government_parsed)
government_df <- government_tables[[1]]

government_df <- rename(government_df,
                        c("Amtsinhaber" = "name"))
government_df <- rename(government_df,
                        c("Partei" = "party"))


government_df$name <- as.character(government_df$name)
government_df$partei <- as.character(government_df$party)
government_df$number <- 0:15

government_df <- government_df[, -1]
government_df <- government_df[, -1]
```

As a result, we get the following:


```r
head(government_df)
```

```
##                      name party partei number
## 1           Angela Merkel   CDU    CDU      0
## 2          Sigmar Gabriel   SPD    SPD      1
## 3 Frank-Walter Steinmeier   SPD    SPD      2
## 4      Thomas de Maizière   CDU    CDU      3
## 5              Heiko Maas   SPD    SPD      4
## 6       Wolfgang Schäuble   CDU    CDU      5
```

Next, we construct a second data frame, `count_df`, to store pairs of politicians together with corresponding numeric IDs. We will later use this data frame for our API queries with the `fromZeit()` function:


```r
i <- 1
from <- NULL
to <- NULL

while (i <= nrow(government_df)){
  j <- i + 1
  while (j <= nrow(government_df)){
    from <- rbind(from, government_df$name[i])
    to <- rbind(to, government_df$name[j])
    j <- j + 1
  }
  i <- i + 1
}

count_df <- as.data.frame(from, stringsAsFactors = FALSE)
count_df <- rename(count_df, c("V1" = "from"))
count_df$to <- as.character(to)

count_df$fromNumber <- NA
count_df$toNumber <- NA

i <- 1
while (i <= nrow(count_df)){
  j <- 1
  while(j <= nrow(government_df)){  
    if (government_df$name[j] == count_df$to[i]){
      count_df$toNumber[i] <- government_df$number[j]
    }
    j <- j + 1
  }
  i <- i + 1
}

i <- 1
while (i <= nrow(count_df)){
  j <- 1
  while(j <= nrow(government_df)){  
    if (government_df$name[j] == count_df$from[i]){
      count_df$fromNumber[i] <- government_df$number[j]
    }
    j <- j + 1
  }
  i <- i + 1
}
```

The first rows of the data frame show connections from Angela Merkel to some members of the cabinet:


```r
head(count_df)
```

```
##            from                      to fromNumber toNumber
## 1 Angela Merkel          Sigmar Gabriel          0        1
## 2 Angela Merkel Frank-Walter Steinmeier          0        2
## 3 Angela Merkel      Thomas de Maizière          0        3
## 4 Angela Merkel              Heiko Maas          0        4
## 5 Angela Merkel       Wolfgang Schäuble          0        5
## 6 Angela Merkel           Andrea Nahles          0        6
```


## Performing the queries and counting

Now R is prepared to perform the actual queries. For each query, we paste together a pair of names from the `count_df` data frame. When executing the query with `fromZeit()`, we specify `limit = 1` because we are only interested in the numbers found in the respective period, and restrict results to the current government period:


```r
zeitSetApiKey("set_your_api_key_here")

count_df$count <- 0
i <- 1

while (i <= nrow(count_df)){
  query = paste(count_df$from[i],
                count_df$to[i], sep = " ")
  articles <- fromZeit(q = query,
                       limit = "1",
                       dateBegin = "2014-01-01",
                       dateEnd = "2015-08-10")
  count_df$count[i] <- count_df$count[i] + as.numeric(articles$found)
  Sys.sleep(0.5)
  i <- i + 1
}
```

Next, we construct a variable `mentioned` that counts the number of articles in which the name of a government member is mentioned:


```r
i <- 1
government_df$mentioned <- 0

while (i <= nrow(government_df)){
  j <- 1
  while (j <= nrow(count_df)){
    government_df$mentioned[i] <- ifelse(count_df$from[j] == government_df$name[i],
                                         government_df$mentioned[i] + count_df$count[j],
                                         government_df$mentioned[i])
    j <- j + 1
  }
  i <- i + 1
}

i <- 1
while (i <= nrow(government_df)){
  j <- 1
  while (j <= nrow(count_df)){
    government_df$mentioned[i] <- ifelse(count_df$to[j] == government_df$name[i],
                                         government_df$mentioned[i] + count_df$count[j],
                                         government_df$mentioned[i])
    j <- j + 1
  }
  i <- i + 1
}
```


For aesthetic reasons, we rescale the `mentioned` variable:


```r
government_df$mentioned <- round(government_df$mentioned / max(government_df$mentioned) * 500)
```

Now, we are ready to visualize the connections.



## Plotting the network

Before we plot the network, we restrict the sample of `count_df` to those connections that appear more than 10 times, which eases interpretation of the network graph.


```r
sample <- count_df[count_df$count > 10, ]
```

Lastly, we visualize the network using the `forceNetwork()` function from the fabulous `networkD3` package. The number of shared articles define the strength of the edges. The node size is defined by total numbers of mentions. What do we find? Rather unsurprisingly, Angela Merkel as well as her 'most important' ministers Sigmar Gabriel (economy and energy), Frank-Walter Steinmeier (foreign affairs), and Wolfgang Schäuble (finance) are mentioned most often in the articles and hold strong connections to the chancellor. Other ministers like Manuela Schwesig (family) and Heiko Maas (justice) also received decent coverage, but apparently on isolated topics---they do not show strong links to other departments. Finally, we also find that some ministers are fairly isolated, e.g., Johanna Wanka (education and research), Gerd Müller (economic cooperation and development) and Herrmann Gröhe (health). It has to be subject of further analyses to investigate whether this is due to the lack of overlap between their and others' policies or because Merkel has decided to use her policy-making power to focus on issues other than health, education or development.

Feel free to play around a bit with <a href="http://r-datacollection.com/rzeit-network.html">the interactive network plot</a> to develop your own theories of department collaboration in the German government!


```r
forceNetwork(Links = sample,
             Nodes = government_df,
             Source = "fromNumber",
             Target = "toNumber",
             Value = "count",
             NodeID = "name",
             Group = "party",
             Nodesize = "mentioned",
             linkDistance = 200,
             linkWidth = JS("function(d){return d.value / 40}"),
             opacity = 0.8,
             width = 800,
             height = 600,
             legend = TRUE)
```

<a href="http://r-datacollection.com/rzeit-network.html"><img class="intext-img" src="/img/blog/rzeit-network.png"></a>
