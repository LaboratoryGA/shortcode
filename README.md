#Shortcode
A Claromentis component which (when installed) will enable shortcode processing
throughout an Intranet site.

##Installation
The component provided by this module is intended to be embedded in the actual
site template structure. It won't function as intended if embedded only within
the home page, or publish templates.

To install this component, you must have a VI-local copy of the file
"common/htmlfooter.html", and add in the component invocation. The following
is a sample htmlfooter file containing the component invocation:
```html
<include file="../common/get2post.html">

<txt name="im_check_include" visible="1">
	<include file="../communication/im_check.html">
</txt>

</body>
</html>
<component class="ShortcodeParserComponent" />
```

##Using Shortcodes
Once the above component invocation has been completed, you may now use the
following syntax anywhere on the site - even in publish pages:
```
^[ShortcodeClass (arg1="Something" arg2="Something else")]^
```

In the above example, "ShortcodeClass" may be PHP classes whatsoever which
contains a method "show" which takes in a single parameter, which will be an
instance of "ShortcodeContext". This object will contain all the relevant
information about the shortcode called, such as all the arguments, and a "body"
if one has been captured (more on this later).

###Pitfalls/Gotchas
One thing to watch out for is when using the above code from the publish page
editor, *sometimes* the editor will auto-magically change the space directly
**preceeding** the open bracket with an HTML entity ```&nbsp;``` - if this
happens, it will cause the shortcode to become unparsable.

##Advanced Topics
###Shortcode Body
One of the great advantages that shortcodes have over pure components (aside
from being able to use them dynamically in publish pages) is that you can
capture a "body", which will be passed to the shortcode class (exposed within
the "ShortcodeContext" instance).

The syntax for performing this is:
```html
<p> Blah blah blah ... </p>
^[ShortcodeClass (arg1="Something" arg2="Something else") begin]^
	here is some <strong>body</strong> content
^[ShortcodeClass end]^
<p>All is well in the world</p>
```