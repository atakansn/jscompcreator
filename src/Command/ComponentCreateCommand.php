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
            'svelte' => __DIR__ . '/../stubs/svelte.stub',
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
        $path = $this->getComponentsPath();

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
    protected function getComponentsPath(): string
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
        $exists = $this->files->exists("{$this->getComponentsPath()}/$name");

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
        $this->fileGenerateHelper('vue','vue');
    }

    /**
     * Create React Component.
     *
     * @return void
     */
    protected function createReactComponent()
    {
        $this->fileGenerateHelper('react', 'jsx');
    }

    /**
     * Create Svelte Component.
     *
     * @return void
     */
    protected function createSvelteComponent()
    {
        $this->fileGenerateHelper('svelte', 'svelte');
    }

    private function fileGenerateHelper(string $libraryName, string $extension)
    {
        $name = "{$this->argument('componentName')}.{$extension}";

        $this->existsComponentFile($name);

        $path = "{$this->getComponentsPath()}/$name";

        $content = file_get_contents($this->getStub()[$libraryName]);

        if ($extension === 'jsx' && $libraryName === 'react') {
            $componentName = "{$this->argument('componentName')}";
            $content = file_get_contents($this->getStub()[$libraryName]);
            $this->files->put($path, str_replace('{{componentName}}', $componentName, $content));
            $this->components->info("[$name] successfully created. [$path]");
            exit;
        }

        if ($this->option('compositionApi')) {
            $this->files->put($path, file_get_contents($this->getStub()['vue_comp']));
            $this->components->info("[Composition Api] [$name] successfully created. [$path]");
            exit;
        }

        $this->files->put($path, $content);

        $this->components->info("[$name] successfully created. [$path]");
    }

}
