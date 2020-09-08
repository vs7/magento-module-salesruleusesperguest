<?php

$this->run("ALTER TABLE `{$this->getTable('salesrule/rule')}`
ADD COLUMN `vs7_uses_per_guest` INT(11) NOT NULL DEFAULT '0';
");

$this->endSetup();