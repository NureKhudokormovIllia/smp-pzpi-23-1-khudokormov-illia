#!/usr/bin/env php
<?php

class SpringStore
{
    private $products = [];
    private $cart = [];
    private $userName = '';
    private $userAge = 0;

	public function __construct()
    {
        $this->loadProductsFromFile('products-php-task3.txt');
    }
	
	private function loadProductsFromFile($filename)
    {
        if (!file_exists($filename)) {
            print "ПОМИЛКА! Файл з товарами не знайдено.\n";
            exit(1);
        }

        $json = file_get_contents($filename);
        $data = json_decode($json, true);

        if ($data === null || !is_array($data)) {
            print "ПОМИЛКА! Неможливо розпарсити файл товарів.\n";
            exit(1);
        }

        $this->products = $data;
    }

    public function run()
    {
        while (true) {
            $this->showMainMenu();
            $command = trim(fgets(STDIN));

            switch ($command) {
                case '0':
                    exit(0);
                case '1':
                    $this->selectProducts();
                    break;
                case '2':
                    $this->showFinalBill();
                    break;
                case '3':
                    $this->configureProfile();
                    break;
                default:
                    print "ПОМИЛКА! Введіть правильну команду\n";
                    $this->showMainMenuOptions();
                    break;
            }
        }
    }

    private function showMainMenu()
    {
        print "\n################################\n";
        print "# ПРОДОВОЛЬЧИЙ МАГАЗИН \"ВЕСНА\" #\n";
        print "################################\n";
        $this->showMainMenuOptions();
    }

    private function showMainMenuOptions()
    {
        print "1 Вибрати товари\n";
        print "2 Отримати підсумковий рахунок\n";
        print "3 Налаштувати свій профіль\n";
        print "0 Вийти з програми\n";
        print "Введіть команду: ";
    }

    private function selectProducts()
    {
        while (true) {
            $this->showProductsList();
            print "Виберіть товар: ";
            $productNumber = trim(fgets(STDIN));

            if ($productNumber === '0') {
                $this->showMainMenuOptions();
                return;
            }

            if (!isset($this->products[$productNumber])) {
                print "ПОМИЛКА! ВКАЗАНО НЕПРАВИЛЬНИЙ НОМЕР ТОВАРУ\n\n";
                continue;
            }

            $selectedProduct = $this->products[$productNumber];
            print "Вибрано: {$selectedProduct['name']}\n";
            print "Введіть кількість, штук: ";
            $quantity = trim(fgets(STDIN));

            if (!is_numeric($quantity) || $quantity < 0 || $quantity >= 100) {
                print "ПОМИЛКА! Ви вказали неправильну кількість товару\n";
                $this->showCartStatus();
                continue;
            }

            if ($quantity == 0) {
                if (isset($this->cart[$productNumber])) {
                    unset($this->cart[$productNumber]);
                }
                $this->showCartStatus();
            } else {
                $this->cart[$productNumber] = [
                    'name' => $selectedProduct['name'],
                    'price' => $selectedProduct['price'],
                    'quantity' => $quantity
                ];
                print "У КОШИКУ:";
                $this->showCart();
            }
        }
    }

    private function showCartStatus()
    {
        if (empty($this->cart)) {
            print "КОШИК ПОРОЖНІЙ\n";
        } else {
            print "У КОШИКУ:\n";
            $this->showCart();
        }
    }
    
    private function showCart()
    {
        $nameWidth = 26;
        
        printf("\n%-{$nameWidth}s %-4s\n", "НАЗВА", "КІЛЬКІСТЬ");
        
        foreach ($this->cart as $item) {
            $name = $item['name'];
            $quantity = $item['quantity'];
            
            $nameLength = $this->utf8_strlen($name);
            
            $correct_value = $nameWidth - $nameLength;
            $correct_value -= 5;
            $padding = str_repeat(' ', max(0, $correct_value));
            
            printf("%s%s %-4d\n", $name, $padding, $quantity);
        }
		
		printf("\n");
    }

    private function utf8_strlen($str)
    {
        $length = 0;
        $i = 0;
        while ($i < strlen($str)) {
            $char = ord($str[$i]);
            if ($char >= 128) {
                if (($char & 0xE0) == 0xC0) {
                    $i += 2;
                } elseif (($char & 0xF0) == 0xE0) {
                    $i += 3;
                } elseif (($char & 0xF8) == 0xF0) {
                    $i += 4;
                }
            } else {
                $i++;
            }
            $length++;
        }
        return $length;
    }
    
    private function showProductsList()
    {
        $nameWidth = 27;
    
        printf("%-2s %-{$nameWidth}s %-4s\n", "№", "НАЗВА", "ЦІНА");
    
        foreach ($this->products as $id => $product) {
            $name = $product['name'];
            $price = $product['price'];
    
            $nameLength = $this->utf8_strlen($name);
    
            $correct_value = $nameWidth - $nameLength;
            $correct_value -= 6;
            $padding = str_repeat(' ', max(0, $correct_value));
    
            printf("%-2d %-s%s %-4d\n", $id, $name, $padding, $price);
        }
    
        print "   -----------\n";
        print "0  ПОВЕРНУТИСЯ\n";
    }


    private function showFinalBill()
    {
        if (empty($this->cart)) {
            print "КОШИК ПОРОЖНІЙ. НІЧОГО КУПУВАТИ\n\n";
            $this->showMainMenuOptions();
            return;
        }
    
        print "№  НАЗВА                 ЦІНА  КІЛЬКІСТЬ  ВАРТІСТЬ\n";
        $total = 0;
        $i = 1;
        $nameWidth = 21;
    
        foreach ($this->cart as $item) {
            $name = $item['name'];
            $price = $item['price'];
            $quantity = $item['quantity'];
            $value = $price * $quantity;
            $total += $value;
            
            $nameLength = $this->utf8_strlen($name);
            
            $correct_value = $nameWidth - $nameLength;
            $padding = str_repeat(' ', max(0, $correct_value));
            
            printf("%-2d %s%s %-5d %-10d %-9d\n", $i, $name, $padding, $price, $quantity, $value);
            $i++;
        }
    
        printf("РАЗОМ ДО CПЛАТИ: %d\n\n", $total);
        $this->showMainMenuOptions();
    }
    
    private function configureProfile()
    {
        while (true) {
            print "Ваше імʼя: ";
            $name = trim(fgets(STDIN));

            if (empty($name) || !preg_match('/[a-zA-Zа-яА-ЯіІїЇєЄґҐ]/u', $name)) {
                print "ПОМИЛКА! Імʼя може містити лише літери, апостроф «'», дефіс «-», пробіл\n\n";
                continue;
            }

            $this->userName = $name;
            break;
        }

        while (true) {
            print "Ваш вік: ";
            $age = trim(fgets(STDIN));

            if (!is_numeric($age) || $age < 7 || $age > 150) {
                print "ПОМИЛКА! Користувач повинен мати вік від 7 та до 150 років\n\n";
                continue;
            }

            $this->userAge = (int)$age;
            break;
        }

        print "\nВаше імʼя: {$this->userName}\n";
        print "Ваш вік: {$this->userAge}\n\n";
        $this->showMainMenuOptions();
    }
}

$springStore = new SpringStore();
$springStore->run();