---
author: Dave Kerr
type: posts
date: "2020-09-05"
description: "Unit testing the Windows Registry can be challenging - this article shares a few tips on how to make it easier."
slug: unit-testing-the-windows-registry
title: Unit Testing the Windows Registry
categories:
- ".NET"
- "C#"
- "Windows"
- "Testing"
tags:
- ".NET"
- "C#"
- "Testing"
- "Windows"
- "CodeProject"
---

I've been updating some of my .NET projects recently (read more about this in [Modernising .NET projects for .NET Core and beyond!](https://dwmkerr.com/modernising-dotnet-projects/)). In one of these projects I have to work with the [Windows Registry](https://en.wikipedia.org/wiki/Windows_Registry) - which can be quite painful, particularly if you want to make your code unit test friendly.

In this article I'm going to introduce a simple approach to make testing the registry a little easier. If you are just interested in the code and not so much the story behind it, you can skip straight to the project at [github.com/dwmkerr/dotnet-windows-registry](https://github.com/dwmkerr/dotnet-windows-registry).

<!-- vim-markdown-toc GFM -->

* [Why Bother Testing?](#why-bother-testing)
* [Why Bother Testing the Registry?](#why-bother-testing-the-registry)
* [Talk is cheap, show me the code](#talk-is-cheap-show-me-the-code)
* [The Registry is not easily testable](#the-registry-is-not-easily-testable)
* [The Testable Registry](#the-testable-registry)
* [Go forth and test](#go-forth-and-test)

<!-- vim-markdown-toc -->

# Why Bother Testing?

There is a wealth of material available on the subject of testing. The value different of different types of tests has been discussed at length and is a constant source of debate. If you are interested in reading about testing in more detail, I recommend [Martin Fowler's Software Testing Guide](https://martinfowler.com/testing/).

I'm not going to weigh in on the debate of the value of different tests. Instead, here are the specific issues I faced when working on my [SharpShell](https://github.com/dwmkerr/sharpshell) project (which is where my registry testing project originated):

1. This is an open source project with a number of users, who would be inconvenienced if things broke from one release to another
2. There are a number of scenarios in the project which involve extensive modification of the registry
3. Even very small mistakes in the way the registry is accessed can break the code
4. Manually testing these scenarios is _very_ time consuming...
5. ...and I have very limited time to work on this project
6. I want to encourage others to contribute, but have confidence their changes will not cause unexpected failures

In _this_ project, being able to test the changes my code is going to make to the registry has been valuable. Whether it is for your own projects will depend on your own circumstances.

# Why Bother Testing the Registry?

The Registry is essentially a database. A problematic database. It has a complex schema, which has evolved over time. The schema for certain features (such as Windows Shell Extensions) has changed considerably over the years. It is often messy - many programs will write to it and programs can overwrite values.

One thing I have discovered over my years maintaining the SharpShell project is that registry access is one of the most _brittle_ elements of the code. It is risky, it can have unexpected consequences.

There are a few things which should cause anyone working with the registry to seriously consider testing:

- What do you do if the keys you are accessing have been modified by other programs?
- What if your own programs have written incorrect data?
- Is your code going to run on different versions of Windows, which might use the registry in different ways?
- Registry access is _security sensitive_ - does your code run with the appropriate permissions to access what it needs to access?

The registry is a database, but it is not an ACID database, meaning you can quite easily end up writing data in an inconsistent format (for example, if your program crashes before it has written all of the data it needs to). It has very limited access control - there is no way to limit other privileged programs overwriting or corrupting your data.

Hopefully covers some of the reasons it is worth testing the registry. Now lets see some code.

# Talk is cheap, show me the code

Here's an example of what I want to be able to do:

```cs
[Test]
public void Register_Server_Associations_Uses_Appropriate_Class_Id_For_Class_Of_Extension()
{
    //  Pretty important test. Given we have a file extension in the registry, assert that we
    //  register an extension with the appropriate ProgID.

    //  Prime the registry with a progid for *.exe files.
    _registry.AddStructure(RegistryView.Registry64, string.Join(Environment.NewLine, 
        @"HKEY_CLASSES_ROOT",
        @"  .exe",
        @"    (Default) = exefile",
        @"    Content Type = application/x-msdownload",
        @"  exefile",
        @"    (Default) = Application"
        ));

    //  Register a context menu with an *.exe association.
    var clsid = new Guid("00000000-1111-2222-3333-444444444444");
    var serverType = ServerType.ShellContextMenu;
    var serverName = "TestContextMenu";
    var associations = new[] { new COMServerAssociationAttribute(AssociationType.ClassOfExtension, ".exe") };
    var registrationType = RegistrationType.OS64Bit;
    ServerRegistrationManager.RegisterServerAssociations(clsid, serverType, serverName, associations, registrationType);

    //  Assert we have the new extention.
    var print = _registry.Print(RegistryView.Registry64);
    Assert.That(print, Is.EqualTo(string.Join(Environment.NewLine,
        @"HKEY_CLASSES_ROOT",
        @"  .exe",
        @"    (Default) = exefile",
        @"    Content Type = application/x-msdownload",
        @"  exefile",
        @"    (Default) = Application",
        @"    ShellEx",
        @"      ContextMenuHandlers",
        @"        TestContextMenu",
        @"          (Default) = {00000000-1111-2222-3333-444444444444}")
    ));
}
```

This test looks a little complex, but the details don't matter. What matters is the _flow_, which is just:

1. **Given** a particular existing structure in the registry...
2. ...**when** I call a certain API...
3. ...**then** I expect a certain set of changes to have been made

Should be easy right? Unfortunately, it's not as easy as this.

# The Registry is not easily testable

The [.NET Framework Registry classes](https://docs.microsoft.com/en-us/dotnet/api/microsoft.win32.registrykey) are not written with testing in mind. This is not surprising - they are just wrappers around the [Win32 Registry APIs](https://docs.microsoft.com/en-us/windows/win32/sysinfo/registry-functions). These are APIs which have been around for a while, they have a very well-defined goal, which is to provide access to the registry. They were not written with unit testing in mind.

There are in general two approaches which can be taken to testing [_side effects_](https://en.wikipedia.org/wiki/Side_effect_(computer_science)). Side effects are changes to state _outside_ of your function or code's state - such as the file system, databases and so on. These approaches are:

- Test the System: We allow our tests to change the external system, making sure to prepare it in advance, read the changes, then clean up afterwards
- Mocks the System: We make sure our code doesn't touch the external system when it is testing, we test a mock only and assert that the mocked code makes the expected changes

The first approach is arguably better - you are _really_ asserting that the expected changes have been made. But it is also complex - you have to clean up after yourself, you run the risk of your tests actually changing (or even breaking your system) and you make it harder to have other developers easily run the tests. Some systems can mitigate this - for example, with some databases you could test in the context of a transaction which you never commit. But the registry offers no such capabilities.

The second approach is more common and in general a little easier. It doesn't cause side effects, but still allows us to at least ensure we are going to attempt to make the expected changes.

To mock a service under test in .NET, we generally need to be calling functions on an _interface_. There are some ways around this (fakes, modified assemblies, etc) but they are problematic. However, the .NET Registry classes are not exposed as interfaces. This is not a failure of the framework, arguably adding interfaces without a specific need is an anti-pattern. But it does make mocking the registry hard.

The easiest way around this problem (at least in my opinion) is to wrap the registry access in an interface, then provide two implementations. One which uses the standard registry access methods, and one which mocks the changes to the registry in an isolated and testable fashion. In my SharpShell code this was the approach I took, and I have just extracted this code into its own library to help others who might want to use the same approach.

# The Testable Registry

The solution I've used is fairly simple. You can see the code at:

[github.com/dwmkerr/dotnet-windows-registry](https://github.com/dwmkerr/dotnet-windows-registry)

Instead of making calls to `Regsitry` or `RegistryKey`, you make calls to `IRegsitry` or `IRegsitryKey`. Then use the appropriate implementation. There are examples in the project documentation, but here's how it looks in a nutshell.

First, make sure the code you have which access the registry does it via the `IRegistry` interface:

```cs
public class Greeter
{
    public Greeter(IRegistry _registry)
    {
        _registry = registry;
    }

    public void Greet(string name, string greeting)
    {
        using var key = registry.OpenBaseKey(RegistryHive.CurrentUser, RegistryView.Registry64);
        using var subkey = key.OpenSubKey("Greetings");
        subkey.SetValue(name, $"{greeting}, {name}!");
    }

    private IRegsitry _registry;
}
```

Now in your program, create your class and provide it with a `WindowsRegistry` class:

```cs
var greeter = new Greeter(new WindowsRegistry());
greeter.Greet("Billy", "Howdy");
```

And you can test your code like so:

```cs
var registry = new InMemoryRegistry();
var greeter = new Greeter(registry);
var print = _registry.Print(RegistryView.Registry64);
Assert.That(print, Is.EqualTo(string.Join(Environment.NewLine,
    @"HKEY_CURRENT_USER",
    @"  Greetings",
    @"     Billy = Howdy, Billy!")));
```

That's the basics.

# Go forth and test

There is a degree of inconvenience in having to use the interface rather than using the out-of-the-box implementation. This is a trade-off you will have to make to allow your code to be testable, and whether it is a worthwhile trade will depend on your project.

The pattern of not relying on concrete implementations and instead providing interfaces to classes is known as [Dependency Injection](https://en.wikipedia.org/wiki/Dependency_injection). There are technologies which attempt to assist with this pattern, known as Inversion of Control Containers - whether they make life easier to simply move complexity around (see [The Law of Conservation of Complexity](https://github.com/dwmkerr/hacker-laws#the-law-of-conservation-of-complexity-teslers-law)). But if you are _already using_ an IoC container then adopting this library and pattern will be trivial.

That's it - the code has been internal to the SharpShell project for years and I have only just extracted it into its own library. I'll be using it in my [ComAdmin](https://github.com/dwmkerr/dotnet-com-admin) project (which is also being extracted from SharpShell). Given that it is new it might change a bit, and I'd love any feedback:

https://github.com/dwmkerr/dotnet-windows-registry
