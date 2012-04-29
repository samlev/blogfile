BLOGFILE
========

This is a simple morning project that I started to see if it was possible to build
a reasonably sophisticated blog in a single PHP file, without having it blow out
to massive proportions.

Of course, this means:
* no images, unless I want to base64 encode them, and tell the PHP file to decode
  and spit them out.
* CSS and Javascript are in the same file.
* Installation (including MySQL schema, etc.) is also in the same file.
* Things may get interesting...

Why one file?
-------------

Why not? Actually, don't answer that...

I wanted to produce a piece blog software which installs from a single file so that
users can install it quickly and easily, and not have to worry about unzipping things
on the server, or ensuring that the folder structure is right.

Actually, that's a lie; I wanted to do it because it seemed ammusing to me. I don't
expect anyone to use this, or that if they do use it, they're the type of person
who would be stymied by an FTP program.

I'm trying to explore (a little) what design patterns I can use to create sophisticated
software in a single file.

So what about config files?
---------------------------

OK, so there won't technically be only one file once it's installed, but everything
you need to install it will be self-contained. Once it's installed, there will be
the main blog file, a config file, and possibly a .htaccess (Apache configuration)
file. Any skinning that you do that contains images, or external css files are going
to count as extra files, but you're doing that, not me. The blog will still have
only a single file as an entry point, so I think that that should count for something.

Don't you know that PHP is horrible? Why not Python/Ruby/Lisp/Cobol/Fortran/Go/C?
---------------------------------------------------------------------------------

Because I find PHP fun. It has it's limitations, sure, but I enjoy it. If you want
to re-create this project in some other language, then go ahead. I'm not stopping
you.

You mentioned skinning?
-----------------------

Yeah, I'm trying to build a basic templating engine into the code, too... because
I guess I'm an idiot. All the template stuff is at the top of the file, where it's
easy to find. It's pretty much straight HTML/CSS/JavaScript with the ability to
call in other templates, or open slots for content with a few simple tags.

This is really basic, and probably easy as hell to break. I'm not trying to rebuild
smarty, or TAL here (although it's probably closer to TAL than smarty... slightly).
All I'm trying to do is provide a simple way to separate logic from templates.

If you want to skin this thing, here's all you need to know:
* `<%%STARTTEMPLATE TEMPLATE_NAME%%>` - Everything after this until the matching
  `<%%ENDTEMPLATE TEMPLATE_NAME%%>` will become a template.
* There's no nesting of templates (why you'd want to, I don't know). The template
  engine will simply include any `<%%STARTTEMPLATE XXXXX%%>` or `<%%ENDTEMPLATE XXXXX%%>`
  lines in the template until the named template ends.
* While a template can start and end on a single line, everything on the line after
  `<%%ENDTEMPLATE TEMPLATE_NAME%%>` is ignored, so you can't start a new template
  on the same line after another one finished.
* `<%%USETEMPLATE TEMPLATE_NAME%%>` will pull in that template at render time, and
  render it in that position. If the template doesn't exist, the code will chuck
  a tantrum and throw Exceptions at you.
* `<%%OPENSLOT SLOTNAME%%>` will allow you to pass arbitrary things into a template
  (like content). You can have the same slot name in several places in a template,
  and the content will be rendered into all of them. If there's no data for a slot,
  then it will just be blanked out.
* Try not to make circular template requirements... it won't work out well.
* Slot and template names are case sensitive, and can contain letters, numbers,
  and the following symbols: `._-`. Everything else is considered invalid and will
  be ignored.

What about making my posts and comments look pretty?
----------------------------------------------------

A simple MarkDown is as follows:
* `_words that will be italic_`
* `*words that will be bold*`
* `#words that will be monospaced#`
* Urls will be auto-linked.
* Urls can be named like this: `[http://some.url description of url]`
* You can disable URLs being linked in comments.

But I want images! I want to make random words 50 feet high, and hot pink!
--------------------------------------------------------------------------

Well you can see the source code if you want to add those things, but don't ask
me to add them. I just don't see them as being that important. Maybe I'm just a
jerk, though.



I want to contribute a fix... Or add a feature...
-------------------------------------------------

Well you're welcome to do so! Or to keep it to yourself! Whatever!

Licence
-------

I'm inclined to release this on the [WTFPL](http://sam.zoy.org/wtfpl/). I mean,
it'd be nice if people linked to me occasionally, or told other people that I was
a nice guy, but that's not what this project is about.