# Javascript Component Creator

It creates components of popular Javascript frameworks used with Laravel.

## Setup


```bash
  composer require jscomp/creator
```

## Examples

#### Vue Option Api Component
```php
php artisan component:create FooComponent vue
```

#### Vue Composition Api Component
```php
php artisan component:create BarComponent vue -C
```

#### React Component
```php
php artisan component:create BazComponent react
```

#### Svelte Component
```php
php artisan component:create ExampleComponent svelte
```

```php
php artisan component:create <1:componentName> <2:libraryName> <--C|compositionApi>
```

- 1 componentName: Name of component.
- 2 libraryName: The Javascript framework using.
- 3 compositionApi: If you are using vue js, if you do not specify --option it will create the option api component, if you use the --option it will create the composition api component
