# Golang Trial 2

## Organising projects

Still insane that you cannot structure your code how you like.

## Organising files

Cannot follow the pyramid princple

## Import for side effect

> An unused import like fmt or io in the previous example should eventually be used or removed: blank assignments identify code as a work in progress. But sometimes it is useful to import a package only for its side effects, without any explicit use. For example, during its init function, the net/http/pprof package registers HTTP handlers that provide debugging information. It has an exported API, but most clients need only the handler registration and access the data through a web page. To import the package only for its side effects, rename the package to the blank identifier:

> import _ "net/http/pprof"

> This form of import makes clear that the package is being imported for its side effects, because there is no other possible use of the package: in this file, it doesn't have a name. (If it did, and we didn't use that name, the compiler would reject the program.)

This gives *nothing* to the reader to let them know what is going on.
