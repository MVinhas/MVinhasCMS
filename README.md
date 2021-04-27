[![Codacy Badge](https://api.codacy.com/project/badge/Grade/d14907bb1cb64202b0a5b6b8e9e6dc1d)](https://app.codacy.com/gh/MVinhas/mvinhas-blog?utm_source=github.com&utm_medium=referral&utm_content=MVinhas/mvinhas-blog&utm_campaign=Badge_Grade_Settings)
[![Build Status](https://travis-ci.org/MVinhas/mvinhas-blog.svg?branch=master)](https://travis-ci.org/github/MVinhas/mvinhas-blog)
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/cee82487aa6e444084a7353b1aeadc90)](https://www.codacy.com/gh/MVinhas/mvinhas-blog/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=MVinhas/mvinhas-blog&amp;utm_campaign=Badge_Grade)

mvinhas-blog is an MVC CMS blog build from scratch, only with Bootstrap as CSS/JS provider and Twig as a template engine. The primary goal has educational intentions, but it is expected to serve the final user as a simple but functional CMS blog to use for whatever purpose

## Installation

You just have to download the project and create a link on your webserver folder. Then, just update the dependencies by updating composer:

```bash
composer update
```
## Directory Structure
```
config/              Website and Database configurations
controllers/         Part of MVC
database/            Database operations
engine/              Core website functions
migrations/          Database tables creator
models/              Part of MVC
scripts/             Website JS/jQuery
style/               Website CSS
tests/               Unit Tests
views/               Part of MVC
```

## Contributing
I made this as an open project to share with others my thoughts on how a PHP blog should be done; of course, there are other ways (and probably more efficient) to achieve the same goal, and please, feel free to contribute and share your thoughts with me.

## License
[AGPL-3.0](https://choosealicense.com/licenses/agpl-3.0/)