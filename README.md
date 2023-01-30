# Share State

## Goal

Sharing state between different processes in PHP.

### Installation

```bash
composer require chemaclass/shared-state
```

### Motivation

This experiment is motivated by the idea of **sharing states between different processes** that need to be aware of some data in common. At first sight, you might think a regular DB would be suitable for this, like MySQL, PostgreSQL, or even Mongo or Redis... but the idea here is to avoid the complexity of an external DB system and keep it simple. Keep it in a file.

Because they are different processes, there is no simple way to share the state between the processes themselves (that doesn't involve storing the data somewhere else), so we have to rely on PHP extensions or save the data on the persistent disk.

The main idea behind this library is to create a temporal file that will save the state you want to share between the different processes on runtime. And once all the processes consumed that data, it would be safe to remove that file.

That means that the goal is to be able to share a temporal state between processes.

### Example

You can check the example in `example/main.php`:
```bash
cd example
php main.php
```

### Config

This file is optional because each state you want to share is referenced and unique by its id (any raw string). Still, suppose you want to define a custom file name (where to store that temporal value), or define the "minutes difference limit" between the new and old stored record. In that case, you can customize the default behavior of the library.

Create at the root of your project a file named `shared-states.php` where you can define different configurations for different shared states of your application. For example:

```php
<?php
return [
    'id-bool' => [
        'file-name' => 'shared-boolean-state.json',
        'minutes-diff-limit' => 1,
    ],
    'id-string' => [
        'file-name' => 'shared-string-state.json',
    ],
    // ...
];
```

> These are optional settings. The file-name will be generated using the id of the shared-state (aka the key `id-bool` or `id-string` in this example). But you can use any id you like. It doesn't need to be present in this configuration list.
