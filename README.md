# Share State

## Goal

Sharing state between different processes in PHP.

## Motivation

This experiment is motivated by the idea of **sharing states between different processes** that need to be aware of some data in common. At first sight, you might think a regular DB would be suitable for this, like MySQL, PostgreSQL, or even Mongo or Redis... but the idea here is to avoid the complexity of an external DB system and keep it simple. Keep it in a file.

Because they are different processes, there is no simple way to share the state between the processes themselves (that doesn't involve storing the data somewhere else), so we have to rely on PHP extensions or save the data on the persistent disk.

The main idea behind this library is to create a temporal file that will save the state you want to share between the different processes on runtime. And once all the processes consumed that data, it would be safe to remove that file.

That means that the goal is to be able to share a temporal state between processes.
