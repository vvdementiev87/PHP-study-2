<?php

use devavi\leveltwo\Blog\Command\Arguments;
use devavi\leveltwo\Blog\Command\CreateUserCommand;
use devavi\leveltwo\Blog\Exceptions\AppException;
use Psr\Log\LoggerInterface;

$container = require __DIR__ . '/bootstrap.php';
$command = $container->get(CreateUserCommand::class);
// Получаем объект логгера из контейнера
$logger = $container->get(LoggerInterface::class);
try {
    $command->handle(Arguments::fromArgv($argv));
} catch (AppException $e) {
    // Логируем информацию об исключении.
    // Объект исключения передаётся логгеру
    // с ключом "exception".
    // Уровень логирования – ERROR
    $logger->error($e->getMessage(), ['exception' => $e]);
}
