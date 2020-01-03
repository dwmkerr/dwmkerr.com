---
author: Dave Kerr
type: posts
categories:
- Golang
- CodeProject
date: "2016-06-01T22:10:40Z"
description: ""
draft: false
image: /images/2016/06/google-it-1.gif
slug: is-it-worth-persevering-with-golang
tags:
- Golang
- CodeProject
title: Is it worth persevering with Golang?
---


I recently decided to try out [the Go Programming Language](https://golang.org/), by building a little project called [google-it](http://www.github.com/dwmkerr/google-it) which let's me run a google search from a terminal:

![google-it screenshot](images/google-it.gif)

The idea behind the project is simple - avoid jumping into a browser if you need to quickly look up something which you can find in the first line of a Google search result. The idea is to try and stay in the zone. For example, forgotten how to split panes in tmux?

```bash
google-it "split pane tmux"
```

Would probably show enough information to get going without leaving the terminal.

Anyway, the project itself is not that useful[^1] but it seemed like an ideal project to use as a learning exercise for a new language. After perhaps 10-20 hours of learning, coding and messing around, I'm wondering - is it worth persevering with Golang?

**Update 3/6/2016** If you are learning too, check the [Tips for Noobs](#tipsfornoobs) section at the end of the article, great tips from members of the community!

## Why Go[^2]?

The decision to choose Go for this learning exercise was fairly arbitrary. For the last few years I've been using mainly interpretted languages or languages which use a platform (.NET, Node.js, Java etc) and the idea of going back to something which compiles into in good ol' binaries seemed appealing. I'd also heard a lot of good things about Go in general, mostly relating to simplicity, ease of use and concurrency.

## Why Persevere?

That's where I'm looking for guidance. I've collected some of my observations so far, and my overall experience with the language is uninspiring. Anyone who can comment on what makes Go great, or whether my ambivalence is justified will help me decide whether to build my next mess-around project in Go or move on to something else.

## Frustrations So Far

Before I upset anyone, this is all just the opinion of a total Go noob with maybe 15 hours of coding time in Go. But I've been developing using a few different languages and platforms for while.

### Folder structure is way too opinionated

Setup itself is easy, at least on unix or a Mac. But like many coders, I'm anal-retentive about how I like to organise things:

```
~
└───repositories
    ├───github.com
        ├───dwmkerr
            ├───project1
            ├───etc
        ├───organisation1
            ├───etc 
    ├───bitbucket.com
        ├───etc
        
```

This is how I structure my projects on all my machines, and it works for me.

Go forces me to put all of my Go projects in the `$GOPATH`, so now I have:

```
└───repositories
    ├───github.com
        ├───etc
├───go
    ├───src
        ├───github.com
            ├───dwmkerr
                ├───goproject1
```

Which unnecessarily spreads out my projects. Other thoughts:

1. My `src` folder is increasingly cluttered with dependent modules, making it harder to find my own work.
2. Even within the project folder, I have little flexibility. I'd like to have a `src` folder to keep my code in, with just the `README.md` at the root (leaving space for a `docs` folder and others if necessary) - this cannot be done, so [my root folder is cluttered](https://github.com/dwmkerr/google-it).
3. Again, in the project folder itself, [I cannot use sub-folders for code](https://www.reddit.com/r/golang/comments/2lq3it/is_there_a_way_to_arrange_go_code_into_multiple/). Some might argue if you need subfolders you have too much code in one project.

All in all it feels like there are a lot of constraints for structure and organisation, with little benefit.

**Update 3/6/2016** Steve Francia has rightly pointed out that points 2 and 3 are actually wrong, a project can be simply a `main.go` file in the root and a set of submodules, see [this comment](http://www.dwmkerr.com/is-it-worth-persevering-with-golang/#comment-2708416211) for details.

**Update 3/6/2016** A very nice way to separate internal and external go modules is described in [this reddit thread](https://www.reddit.com/r/golang/comments/4m5it3/is_it_worth_persevering_with_golang/d3ssyts).

### The idiomatic approach to error handling is flawed

This is likely to prove contentious.

My code contains sections like this:

```
func LoadSettings() (Settings, error) {

  var s Settings

  exists, err := exists(GetSettingsPath())
  if err != nil {
    return s, err
  }
  if !exists {
    return CreateDefaultSettings(), nil
  }

  raw, err := ioutil.ReadFile(GetSettingsPath())
  if err != nil {
    return s, err
  }

  json.Unmarshal(raw, &s)
  return s, err
}
```

I see smells:

1. The `s` structure is created even though I may not need it.
2. Even worse, it is **returned uninitialised** in error conditions.
3. Repetitive code for dealing with error conditions for calls.

Now I could avoid the first smell by returning a pointer to the structure, but that incures unnecessary complexity and heap allocations. Here I feel the language is forcing me to do something awful (return a structure I know is invalid) and expect the caller to deal with it.

Even worse - the calling code now does this:

```
settings, err := LoadSettings()
if err != nil {
    color.Red("Error loading settings: ", err)
    os.Exit(1)
}
```

I've seen this in many places - nested calls passing the same error around, with little extra context, and eventually terminating.

**This is what exceptions are for.**

Native exceptions in languages handle this for you, giving stack information and killing the process by default.

The 'pass the error on to the caller approach' may not be the right way to go, but the Go blog suggests exactly this:

https://blog.golang.org/error-handling-and-go

And to me it stinks a bit. If this is the truly desired idomatic approach, then why not supported it natively by the language? Here's the same pseudo-code in F#:

```
let loadSettings = 
  let path = getSettingsPath()
  match exists path with
  | true -> path |> readFile |> readSettings
  | _ -> createDefaultSettings

match loadSettings with
| Some settings -> // ..whatever
| None -> // ..deal with errors
```

If `loadSettings` can't return settings, it doesn't return settings. If the caller doesn't handle the 'no settings' scenario explicitly, the compiler will complain that there's a case missing. In this case we have an approach which will warn the coder if they miss something.

### Inconsistent Syntax

A small one, but when I'm defining a structure I can do this:

```
type Link struct {
    Id string
    Uri string
}
```

but when I'm returning a structure, I need commas:

```
return Link{
    Id:  strconv.Itoa(linkNumber),
    Uri: item.Link,
}
```

I can see the benefit of **allowing** a comma on the  last line, to support quick refactoring, but **forcing** it seems odd. Why commas for some constructs and not others[^3]?

Also, some more 'unusual' syntax (depending on your background) is present, I assume to save space:

```
something := createAndAssign()

// rather than
var something SomeType
something = assign()
```

But some space saving constructs such as ternary operators are missing:

```
// easy- c++, c#, java style
something := condition ? case1() : case2()

// easy- python style
something := case1() if condition else case2() // python

// hard - go style
var something SomeType
if condition {
    something = case1()
} else {
    something = case2()
} 
```

### Difficult Debugging

For C, C++, .NET, Java and many other languages, debugging is pretty straightforward. For Node.js, you can just use the excellent Chrome debugging tools. For Go, it seems like it's **much** harder.

In my limited time using the language, I avoided `gdb` because it looked like a lot of work:

https://golang.org/doc/gdb

I did see some projects like [godebug](https://github.com/mailgun/godebug) which may ease the process but I was initially surprised by the effort needed to get into debugging.

Commenter Sotirios Mantziaris [mentioned that delve provides a nice experience as a debugger](http://www.dwmkerr.com/is-it-worth-persevering-with-golang/#comment-2707804888), so this would be worth exploring.

## Delights So Far

It's also worth talking about what I've liked or loved about Go so far.

### Simple Tooling

A project can be nothing more than a single file, go knows how to build and install it. Compare that to Java, where you have a lot of 'project' related stuff - Gradle stuff, Ant stuff, Maven stuff, xml project files stuff and it feels much cleaner.

The tooling is intuitive, fast and works well if you are happy living in a terminal.

### Fantastic Community

I've added this observation just recently, since writing the article I've had a **huge** amount of positive input, describing how to improve my code, better understand Go idioms and where its sweet spots like.

For someone new to a language, the community support is great and will really help people just getting into Go get advice and guidance.

### Testing as a First Class Citizen

Testing is built in, which is great. Knowing that you can run `go test` on a project and have a standard way of executing tests is really quite nice. I love `npm test` for Node.js projects as it has helped standardise testing as a practice (checkout `npm install` then `npm test`).

However, I did have to rely on a library, [goconvey](https://github.com/smartystreets/goconvey), to allow me to write tests in the more BBD structured style which I prefer:

```
func TestSpec(t *testing.T) {

    Convey("The param loader", t, func() {

        Convey("Should handle no params", func() {
            params, err := ParseParams([]string{})
            So(params.ShowHelp.Present, ShouldEqual, false)
            So(params.Results.Present, ShouldEqual, false)
            So(params.Open.Present, ShouldEqual, false)
            So(err, ShouldEqual, nil)
        })
```

But that's a totally personal thing and I'm sure many others will prefer more 'vanilla' tests.

### Great Documentation

I've found everything I've needed so far on [Go's own documentation](https://golang.org/doc/). The documentation is clean, accessible and seems fairly complete from my limited interactions with it.

### Delightful Vim Development Experience

OK - this is not a language feature. But if you are a noob like me moving from this:

![Vim vanilla](images/VimGoVanilla-1.jpg)

to this:

![Vim with vim-go plugin screenshot](images/VimWithVimGo.jpg)

made a big difference. The excellent [vim-go](https://github.com/fatih/vim-go) plugin gives syntax highlighting and supports some really useful commands. As a learner, regularly running `:GoLint` is really helping me write more 'conventional' Go.

## What am I missing?

There are some things I know I haven't had a chance to look at which may really be demonstrating the best parts of Go:

1. Concurrency patterns
2. Performance
3. Writing web servers
4. Godoc
5. Debugging with Delve

## Should I continue?

At this stage I'm leaning towards moving on and trying something different, hoping that I'll come back to Go later. Should I persevere with Go for my next project, would Go enthusiasts suggest so and what sort of project hits the 'sweet spot' where Go is a really effective choice?

An interesting comment by a colleague was: "I would say the usual 'does it change the way you think about programming?', if yes then persevere, if no then are you going to really leverage Go’s strengths (and find out weaknesses) in your project? If no then either change language or project." was rather insightful.

Any comments are welcome!

## Can you help me get better?

Any pull requests to my project or comments which show where I've gone wrong and what I could do to improve my experience and code would be welcome at:

https://github.com/dwmkerr/google-it

Thanks!

## Tips for Noobs!

Since publishing this article I've collected some useful tips from people who've commented or got in touch.

#### Use gofmt and lint

The tool `gofmt` will update your code to format it in a conventional go style. This'll help you keep your code consistent with others'. Using a linter will also help you stay conventional - if you are using [vim-go](https://github.com/fatih/vim-go) you can run it from vim with `:GoLint`. Thanks @snoproblem!

#### Understand where Go is a Ferrari

This I am still working on, and it's certainly tricky for a noob. But commenter devdungeon pointed out that a project like mine is not a great use case for Go - Go excels at speed and concurrency. Projects where that is key are going to be more inspiring. See [this comment](http://www.dwmkerr.com/is-it-worth-persevering-with-golang/#comment-2708265044) for more.

**Footnotes**

[^1]: Mainly because you have to sign up for the Google Cloud Platform to get an API key so you can perform searches, as Google have deprecated the free and easy search API.
[^2]: https://www.youtube.com/watch?v=DvijZuvEiQo
[^3]: SnoProblem describes why in [this comment](http://www.dwmkerr.com/is-it-worth-persevering-with-golang/#comment-2707767852)

