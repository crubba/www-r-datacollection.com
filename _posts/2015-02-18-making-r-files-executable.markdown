---
layout: blog-layout
title:  "Making R Files Executable (under Windows)"
author: "Peter Meissner"
authorref: "http://pmeissner.com"
date: 2015-02-18 14:25:10
teaser-pic: "/img/blog/rexec.png"
category: rstats
blogo: "main"
---

Although it is reasonable that R scripts get opened in *edit mode* by default, it would be even nicer (once in a while) to run them with a simple double-click. Well, here we go ...

### Choosing a new file extension name (.Rexec)
First, we have to think about a new file extension name. While double-click to run is a nice-to-have, the default behaviour should not be overwritten. In the Windows universe one cannot simply attach two different behaviours to the same file extension but we can register new extensions and associate custom defaults to those. Therefore we need another, new file extension.

To make the file extension as self-explanatory as possible, I suggest using  *.Rexec* for R scripts that should be executable while leaving the default system behaviour for *.R* files as is. 

### Associating a new file type with the .Rexec extension
In the next step, we tell Windows that the *.Rexec* file extension is associated with the *RScriptExecutable* file type. Furthermore, we inform Windows how these kind of files should be opened by default. 

To do so, we need access to the command line interface, e.g., via *cmd*. Click **Start** and type **cmd** into the search bar. Instead of hitting enter right away, right click on the **'cmd.exe'** search result, choose **Run as administrator** from the context menu, and click **Yes** on the following pop up window. The windows command line should pop up thereafter. 

Within the command line, type first: 

```
ASSOC .Rexec=RScriptExecutable
```

... then ...

```
FTYPE RScriptExecutable=C:\Program Files\R\R-3.1.2\bin\x64\Rscript.exe  %1 %*
```

... while making sure that the path used above really leads to your most recent/preferred **RScript.exe**.

### Testing 
To test if everything works as expected, create an R script and write the following lines:


```r
message(getwd())
for(i in 1:100) {
  cat(".")
  Sys.sleep(0.01)
}
message("\nBye.")
Sys.sleep(3)
```

Save it as, e.g., **'test.Rexec'** and double click on the file. Now a black box should pop up, informing you about the current working directory, and printing 100 dots on the screen and terminate itself after saying *'Bye'*. 

**Et voilà.**

### One more thing (or two)
While you are now able to produce executable R script files, note that it is also very easy to transform those back by simply changing the file extension from *.Rexec* to *.R* and vice versa. 

If you execute your R scripts from the command line, you might want to save yourself from having to add the file extension every time. Simply register *.Rexec* as a file extension that is executable. The *PATHEXT* environment variable stores all executable file types. Either go to: **Start > Control Panel > System > Advanced System Settings > Environment Variables** and search for the **'PATHEXT'** entry under **System Variables** and add *.Rexec* to the end of the line like that: **'.COM;.EXE;.BAT;.Rexec'**, or go to the command line again and type:

```
set PATHEXT=%PATHEXT%;.Rexec 
```

### Sources of knowledge
* [FTYPE documentation](https://technet.microsoft.com/de-de/library/cc771394(v=WS.10).aspx)
* [ASSOC documentation](https://technet.microsoft.com/de-de/library/cc770920(v=WS.10).aspx)

