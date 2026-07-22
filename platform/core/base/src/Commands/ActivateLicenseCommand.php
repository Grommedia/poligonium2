<?php

namespace Botble\Base\Commands;

use Botble\Base\Commands\Traits\ValidateCommandInput;
use Botble\Base\Exceptions\LicenseIsAlreadyActivatedException;
use Botble\Base\Supports\Core;
use Botble\Setting\Facades\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

use function Laravel\Prompts\text;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputOption;
use Throwable;

#[AsCommand('cms:license:activate', 'Activate license')]
class ActivateLicenseCommand extends Command
{
    use ValidateCommandInput;

    public function __construct(protected Core $core)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        if ($this->option('buyer') && $this->option('purchase_code')) {
            $username = $this->option('buyer');
            $purchasedCode = $this->option('purchase_code');

        }

            return $this->performUpdate($purchasedCode, $username);

    }

    protected function performUpdate(string $purchasedCode, string $username): int
    {
        $status = $this->core->activateLicense($purchasedCode, $username);

        if (! $status) {
            $this->components->error('This license is invalid.');

            return self::FAILURE;
        }

        Setting::forceSet('licensed_to', $username)->save();

        $this->components->info('This license has been activated successfully.');

        return self::SUCCESS;
    }

    protected function configure(): void
    {
        $this->addOption('buyer', null, InputOption::VALUE_REQUIRED, 'The buyer name');
        $this->addOption('purchase_code', null, InputOption::VALUE_REQUIRED, 'The purchase code');
    }
}
