<?php

namespace App\Controller;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BotManController extends AbstractController
{
    /**
     * @Route("/botman", name="botman_index")
     */
    public function index(): Response
    {
        return $this->render('user_nutrition/botman_index.html.twig');
    }

    /**
     * @Route("/botman/handle", name="botman_handle")
     */
    public function handle()
    {
        $config = [];

        DriverManager::loadDriver(\BotMan\Drivers\Web\WebDriver::class);

        $botman = BotManFactory::create($config);

        $botman->hears('Hi', function (BotMan $bot) {
            $bot->reply('Hello! How can I help you with your nutrition and weight loss goals?');
        });

        $botman->hears('What should I eat to lose weight?', function (BotMan $bot) {
            $bot->reply('To lose weight, you should focus on eating nutrient-dense foods that are low in calories and high in fiber. This includes foods like fruits, vegetables, whole grains, lean proteins, and healthy fats. You should also limit your intake of processed foods, sugary drinks, and high-fat foods. Here is a sample meal plan for a day:

- Breakfast: oatmeal with berries and almonds
- Snack: carrot sticks with hummus
- Lunch: grilled chicken salad with mixed greens, cherry tomatoes, cucumber, and avocado
- Snack: apple slices with peanut butter
- Dinner: baked salmon with quinoa and steamed broccoli

Remember to also stay hydrated and exercise regularly to help boost your weight loss efforts.');
        });

        $botman->hears('What should I eat to gain weight?', function (BotMan $bot) {
            $bot->reply('To gain weight, you should focus on eating nutrient-dense foods that are high in calories and protein. This includes foods like lean meats, poultry, fish, eggs, dairy products, whole grains, nuts, seeds, and healthy oils. You should also limit your intake of low-calorie foods and beverages. Here is a sample meal plan for a day:

- Breakfast: whole grain toast with avocado and scrambled eggs
- Snack: Greek yogurt with granola and mixed berries
- Lunch: turkey and cheese sandwich on whole grain bread with a side of fruit salad
- Snack: trail mix with nuts, seeds, and dried fruit
- Dinner: grilled steak with sweet potato and steamed vegetables

Remember to also engage in strength training exercises to help build muscle mass.');
        });

        $botman->listen();
    }
}