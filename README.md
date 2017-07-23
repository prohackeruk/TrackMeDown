# TrackMeDown
First things first, massive credit to DemmSec for first creating this idea. You can learn more about what they did here:
https://www.youtube.com/watch?v=UGa8TIwosEA
https://demmsec.co.uk/2017/02/track-any-smartphone-using-a-simple-web-page/

This is a tool designed to track computers using only the user's browser. As DemmSec stated in their video, this could be useful in a pentest if you get a shell back from an IP address that isn't in the range the client gave you -- it probably means that one of their employees is checking their emails from the local Starbucks.

It could also be used to track a target after you've managed to hook them into BEeF, so you can just set this up on a server and then open the page in the user's browser.

There are no "good" tools and there are no "evil" tools, there are just tools. It's up to you not to use this for evil. If you get yourself in trouble, don't come crying to me. No stalkers allowed.

Features:

Planned features:
* Tracking users using only their phone
* A unique token to identify each tracked user, along with capturing their UserAgent GET header, so you can identify each user you're tracking
* Maps! You will be able to see the path that each user is taking plotted out on a map. Google maps is great.
* Authentication using prohack-id (dat's mine -- it's just a basic identity service in PHP) so that if somebody finds your server, they still can't see the data you've captured without cracking your password 
* Docker support for one-line (if you already have Docker installed :P) setup

Notes:
* You aren't allowed to send Navigator API location data over HTTP, it has to be HTTPS. You can get an SSL cert for your server for free now -- LetsEncrypt is your friend -- so there's no reason not to anymore.
* The user has to accept the use of location services in their browser before you can track them. My advice is to edit the tracking page to have some content that will trick the user into enabling location services. There's a list of trackable browsers later in this file.
* Obviously, if the user loses Internet connection, then they won't be able to send any more location updates, and you'll lose track of them. If you're tracking phones, though, it's possible (even likely, these days) that they have an Internet connection via whatever GSM network they're connected to.

Trackable browsers:
-- Desktop --
* Chrome (Desktop): Yes. Once you've allowed location services for a site once, then Chrome remembers it and always allows you to get their location.
* Chromium (Desktop): Yes. Same as Chrome.
* Firefox (Desktop): No. Every time you ask Firefox to give you the user's location, it prompts them to allow location services once -- in our case, once a second at time of writing. This will be at best annoying and at worst suspicious -- I don't recommend using this tool against a target who you know is using Firefox.
* Safari: Not yet tested.
* Opera: Not yet tested.
* Internet Exploder: Not yet tested.
* Microcock Edgy: Not yet tested.

-- Android --
* Chrome: Not yet tested.
* Firefox: Not yet tested.
* Orfox: Not yet tested.

-- iOS --
* Safari: Not yet tested.
* Chrome: Not yet tested.
* Firefox?: Not yet tested.

-- Windoze Phone --
* Internet Exploiter: Not yet tested.
* Micro$oft Edgelord: Not yet tested.

As always, if you have any suggestions for new features, or you find any bugs, open an issue ticket thing on the repo on Github. If you find a bug/feature that you want to fix/add yourself, then do the usual fork -> fix -> pull request thing. As long as you're not trolling, I'll probably accept it.
