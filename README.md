Programmatically Add Posts
=========================

The addon functionality in this child theme has been pulled directly from [ParentTheme](https://github.com/vc27/ParentTheme/tree/master/includes/classes/CreatePosts) core code. This repository exists as an example for a presentation. The presentation will be given at the February Nashville WPDev breakfast 2014.

### Description
There are a few default ways to add posts to the wpdb, but most methods are fairly limited. WordPress has a function that can do this with relative ease. With the proper implementation you can have a class that you can use for importing content into your site, adding data from a csv file or adding data from an api. Once you're able to programmatically add posts to the wpdb WordPress as a cms can expand into WordPress as an application.

### WP Function used
- [wp_insert_post()](https://codex.wordpress.org/Function_Reference/wp_insert_post)
- [wp_update_post()](https://codex.wordpress.org/Function_Reference/wp_update_post)
- [wp_delete_post()](https://codex.wordpress.org/Function_Reference/wp_delete_post)

ChangeLog
====================

### 02.25.14 - 1.0.1
- add templates for simple [wp_insert_post()](https://codex.wordpress.org/Function_Reference/wp_insert_post), [wp_update_post()](https://codex.wordpress.org/Function_Reference/wp_update_post) and [wp_delete_post()](https://codex.wordpress.org/Function_Reference/wp_delete_post)

### 02.04.14 - 1.0.0
- refactor class and confirm import functionality

### 00.00.00 - 0.0.0
- Initial Commit