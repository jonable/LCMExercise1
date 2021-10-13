# Task [EXERCISE-1]
### Ticket Description:
Hello Last Call Media! We need display the list of users returned from this api (https://deelay.me/3000/https://jsonplaceholder.typicode.com/users) in a table on our homepage. It can appear anywhere on the homepage, we don't care that much about the presentation. Our only requirement is that it appears in a `<table>` and shows the following information for each user:
* `name`
* `username`
* `email`
* `phone`
* `website`

Two additional pieces that would be nice to incorporate, if possible:
1. We are not sure, but I think that in the future we will need to display this listing on other pages, as well. If possible, could this be built in a way that makes it very easy to add to other pages in the future?
2. This API seems pretty slow, but unfortunately we have to use it. The URL must remain _AS-IS_. We're concerned about potential performance issues if this API call needs to be made every time the page is loaded. Could you please try to ensure that this is done in a way that does not impact end users very much?

---

One special thing to note: The information returned from the request needs to be _reasonably_ up to date. If we could make sure that the information that is displayed to site visitors is from within the last hour or so, that would be great.

Thanks so much!\
Your favorite (fake) client.

PS: Please keep all related code in the `exercise1` module, except for any configuration exports that may be needed.
