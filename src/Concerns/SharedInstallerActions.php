<?php

namespace Brunocfalcao\Larapush\Concerns;

use sixlive\DotenvEditor\DotenvEditor;

trait SharedInstallerActions
{
    protected function publishLarapushResources()
    {
        $this->bulkInfo(2, 'Publishing Larapush resources...', 1);
        $this->runProcess('php artisan vendor:publish --provider="Brunocfalcao\Larapush\LarapushServiceProvider" --force --quiet');
    }

    protected function clearConfigurationCache()
    {
        $this->bulkInfo(2, 'Cleaning Configuration cache...', 1);
        $this->runProcess('php artisan config:clear --quiet', getcwd());
    }

    protected function unsetEnvData()
    {
        $env = new DotenvEditor;
        $env->load(base_path('.env'));
        $env->unset('LARAPUSH_TYPE');
        $env->unset('LARAPUSH_TOKEN');
        $env->unset('LARAPUSH_REMOTE_URL');
        $env->unset('LARAPUSH_OAUTH_CLIENT');
        $env->unset('LARAPUSH_OAUTH_SECRET');
        $env->save();
        unset($env);
    }

    protected function gracefullyExit()
    {
        $message = $this->exception->getMessage();

        if ($this->exception instanceof \ErrorException) {
            $message = $this->exception->getMessage().' (line '.$this->exception->getLine().') on '.$this->exception->getFile();
        }

        $message = blank($message) ?
                    'Ups. Looks like this step failed. Please check your Laravel logs for more information' :
                    $message;

        $this->error("An error occurred! => $message");
        exit();
    }
}
