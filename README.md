parallely
=====

parallely is a re-usable component to do parallel-processing with php.

Example Setup
-----
<pre>
"parallel": {
    "adapter": "sharedmemory",
    "config": {
        "path": "/tmp",
        "host": "localhost",
        "port": 11211
    }
}
</pre>

<pre>
$oParallel = \parallely\Builder::build($aStack, $oConfig->adapter, $oConfig->config);
$oParallel->run(array(
    'check' => array(
        $iTime
    ),
    'run'
));
</pre>

Builtin transports
-----

- File
- Memcached
- Shared-Memory
- XCache
- APC

Note
-----
With empty or invalid configuration, parallely will process all stacks sequentially.

Extending
-----
You may add own adapters by simply implementing the TransportInterface.
