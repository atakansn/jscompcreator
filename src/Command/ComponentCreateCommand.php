<?php

namespace JsCompCreator\Command;

use Illuminate\Console\GeneratorCommand;

class ComponentCreateCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'component:create
                            {componentName : Component Name}
                            {libraryName : JS framework(Vue,React,Svelte) used in project.}
                            {--C|compositionApi}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It creates components of Javascript frameworks.';


    /**
     * File structures of components.
     *
     * @return array
     */
    protected function getStub(): array
    {
        return [
            'vue' => __DIR__ . '/../stubs/vue_option_api.stub',
            'vue_comp' => __DIR__ . '/../stubs/vue_composition_api.stub',
            'react' => __DIR__ . '/../stubs/react.stub',
            'svelte' => __DIR__ . '/../src/stubs/svelte.stub',
        ];
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $this->componentsPathExists();

        match ($this->argument('libraryName')) {
            'vue' => $this->createVueComponent(),
            'react' => $this->createReactComponent(),
            'svelte' => $this->createSvelteComponent(),
        };
    }

    /**
     * Checks for the existence of the components folder.
     * Returns the path as a string if the folder exists.
     *
     * @return string
     */
    protected function componentsPathExists()
    {
        $path = $this->componentsPath();

        if (!$this->files->exists($path)) {
            $this->files->makeDirectory($path);
        }

        return $path;
    }

    /**
     * Specifies the path to the components folder.
     *
     * @return string
     */
    protected function componentsPath()
    {
        return resource_path('js/Components');
    }

    /**
     * Checks the presence of the component.
     *
     * @param string $name
     * @return void
     */
    protected function existsComponentFile(string $name)
    {
        $exists = $this->files->exists("{$this->componentsPath()}/$name");

        if ($exists) {
            $this->components->error('This file is exists!');
            exit;
        }

        $this->components->info('Component is created!');
    }

    /**
     * Vue Component.
     * If -C option is used, it creates composition api component.
     *
     * @return void
     */
    protected function createVueComponent()
    {
        $name = "{$this->argument('componentName')}.vue";

        $this->existsComponentFile($name);

        $path = "{$this->componentsPath()}/$name";

        if ($this->option('compositionApi')) {
            $this->files->put($path, file_get_contents($this->getStub()['vue_comp']));
            $this->components->info("[Composition Api] [$name] successfully created. [$path]");
            exit;
        }

        $this->components->info("[$name] successfully created. [$path]");

        $this->files->put("{$this->componentsPath()}/$name", file_get_contents($this->getStub()['vue']));

    }

    /**
     * Create React Component.
     *
     * @return void
     */
    protected function createReactComponent()
    {
        $componentName = $this->argument('componentName');

        $name = "{$componentName}.jsx";

        $this->existsComponentFile($name);

        $path = "{$this->componentsPath()}/$name";

        $content = file_get_contents($this->getStub()['react']);

        $this->components->info("[$name] successfully created. [$path]");

        $this->files->put($path, str_replace('{{componentName}}', $componentName, $content));

    }

    /**
     * Create Svelte Component.
     *
     * @return void
     */
    protected function createSvelteComponent()
    {
        $name = "{$this->argument('componentName')}.svelte";

        $this->existsComponentFile($name);

        $path = "{$this->componentsPath()}/$name";

        $content = file_get_contents($this->getStub()['svelte']);

        $this->components->info("[$name] successfully created. [$path]");

        $this->files->put($path, $content);
    }

}
