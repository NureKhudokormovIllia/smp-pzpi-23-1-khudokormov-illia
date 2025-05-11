<?php
session_start();

require_once 'functions.php';

include 'tpl/header.phtml';
?>

<div class="container">
    <div class="row">
        <div class="column">
            <h2>Про наш магазин</h2>
            
            <div class="about-content">
                <p>Ласкаво просимо до нашого інтернет-магазину! Ми раді запропонувати вам широкий асортимент якісних товарів за доступними цінами.</p>
                
                <h4>Наші переваги:</h4>
                <ul>
                    <li>Зручний інтерфейс для вибору та замовлення товарів</li>
                    <li>Швидка доставка</li>
                    <li>Висока якість обслуговування</li>
                    <li>Гарантія на всі товари</li>
                </ul>
                
                <h4>Як з нами зв'язатися:</h4>
                <p>Телефон: +38 (050) 123-45-67</p>
                <p>Email: illia.khudokormov@nure.ua</p>
                <p>Адреса: м. Харків, вул. Адигейська, 21</p>
                
                <h4>Години роботи:</h4>
                <p>Пн-Пт: 9:00 - 18:00</p>
                <p>Сб-Нд: 10:00 - 16:00</p>
            </div>
        </div>
    </div>
</div>

<?php include 'tpl/footer.phtml'; ?>