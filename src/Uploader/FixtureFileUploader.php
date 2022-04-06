<?php

/*
 * This file is part of Monsieur Biz' Rich Editor plugin for Sylius.
 *
 * (c) Monsieur Biz <sylius@monsieurbiz.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MonsieurBiz\SyliusRichEditorPlugin\Uploader;

use Gaufrette\FilesystemInterface;
use Symfony\Component\HttpFoundation\File\File;
use Webmozart\Assert\Assert;

final class FixtureFileUploader implements FixtureFileUploaderInterface
{
    private FilesystemInterface $filesystem;

    public function __construct(FilesystemInterface $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function upload(File $file, string $target): void
    {
        Assert::isInstanceOf($file, File::class);

        if ($this->filesystem->has($target)) {
            $this->remove($target);
        }

        $this->filesystem->write(
            $target,
            (string) file_get_contents($file->getPathname())
        );
    }

    public function remove(string $path): bool
    {
        if ($this->filesystem->has($path)) {
            return $this->filesystem->delete($path);
        }

        return false;
    }
}
