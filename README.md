Results of the test:

## Static Content

```
Fast Expenses: 1, 2, 3, 1, 2
Fast Elapsed time: 556

Slow Expenses: 1, 2, 3, 1, 2
Slow Elapsed time: 545
```

Hence, for some reason requests still behave like consecutive for static.

## Dynamic content (sleep(1))

```
Fast Expenses: 1, 2, 3, 4, 5, 6, 7, 8
Fast Elapsed time: 2014
Slow Expenses: 1, 2, 3, 4, 5, 6, 7, 8
Slow Elapsed time: 2008
```
