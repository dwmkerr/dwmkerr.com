---
author: Dave Kerr
categories:
- SVN
date: "2011-11-14T07:55:00Z"
description: ""
draft: false
slug: getting-source-code-metrics-from-svn
tags:
- SVN
title: Getting Source Code Metrics from SVN
---


<p>Lets say that we need to find out how many lines of code exist in a branch, or how many lines are checked in by a specific user. Let's ignore the usefulness of these metrics, just assume that they're needed (realistically, lines of code isn't a very useful metric, but perhaps you want to have a quick idea of how much has gone into a release). How do we do this?</p>
<p>TortoiseSVN statistics aren't really enough. Here's some alternatives:</p>
<ul>
<li>SVNPlot<br />Theoretically should give us graphs. Runs in python. Couldn't get it to work in five minutes so moved on.<br /><a href="http://code.google.com/p/svnplot/">http://code.google.com/p/svnplot/</a></li>
<li>StatSVN<br />Much more respected than the above, runs through Java. Again, didn't have results in five minutes to moved on.<br /><a href="http://www.statsvn.org/">http://www.statsvn.org/</a></li>
<li>FishEye<br />Very powerful but it's not free. Generates a lot of information that you can use to analyse your repositories.<br /><a href="http://www.atlassian.com/software/fisheye/overview?gclid=CN6cw4WptqwCFQRP4QodnCtcGg">http://www.atlassian.com/software/fisheye/overview?gclid=CN6cw4WptqwCFQRP4QodnCtcGg</a>&nbsp;</li>
</ul>
<p>I'd recommend taking a look at FishEye if you're going to go to the effort of getting these statistics. Any comments on alternatives would be welcome!</p>

