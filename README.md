parallely
=====

parallely is a re-usable component to do easy notifying, e.g. for status reports or updates.
This is especially useful in cli applications where it's not longer necessary to continuously monitor the jobs output or to tail a log-file.

Builtin transports
-----

- File
- Memcached
- Shared-Memory
- XCache
- APC

Extending
-----
You may add own adapters by simply implementing the TransportInterface.
