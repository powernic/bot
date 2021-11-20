<?php

use Symfony\Component\Translation\MessageCatalogue;

$catalogue = new MessageCatalogue('ru', array (
  'messages' => 
  array (
    'exception.policy.add.policy' => 'Не удается добавить полис',
    'exception.policy.add.message' => 'Не удается сохранить данные полиса',
    'exception.unexpected.request' => 'Вы батенька, что-то не то делаете!',
    'validator.policy.code' => 'Номер полиса должен иметь длину {{ limit }} символов',
    'validator.policy.date' => 'Дата указана неверно',
    'emias.policy.edit.info' => 'Ваш полис: %name% Номер: %code% Дата рождения: %date% Введите новое название полиса:',
    'emias.policy.add.name' => 'Название полиса:',
    'emias.policy.add.code' => 'Номер полиса:',
    'emias.policy.add.date' => 'Дата рождения в формате Год-Месяц-День, например: (2020-03-30)',
    'emias.policy.add.success' => 'Полис "%police_name%" успешно добавлен!',
  ),
));


return $catalogue;
