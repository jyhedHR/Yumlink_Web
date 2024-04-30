<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChatController extends AbstractController
{
    #[Route('/chat', name: 'chat', methods: ['GET', 'POST'])]
    public function chat(Request $request): Response
    {
        // If the request method is GET, render the chat Twig template
        if ($request->isMethod('GET')) {
            return $this->render('user_nutrition/chat.html.twig');
        }

        // If the request method is POST, handle the chat message
        if ($request->isMethod('POST')) {
            // Get the message sent by the user
            $message = $request->request->get('message');

            // Define conversation triggers and handle responses
            $response = $this->defineConversationTriggers($message);

            // Return a JSON response with the bot's response
            return new Response(json_encode(['content' => $response]), Response::HTTP_OK, ['Content-Type' => 'application/json']);
        }

        // Return a 404 response for other request methods
        return new Response(null, Response::HTTP_NOT_FOUND);
    }

    // Define conversation triggers and handle responses
    private function defineConversationTriggers($message)
    {
        if (stripos($message, 'What should I eat to lose weight?') !== false) {
            return $this->replyToLoseWeight();
        } elseif (stripos($message, 'What should I eat to gain weight?') !== false) {
            return $this->replyToGainWeight();
        }
        // Add more conversation triggers as needed

        // Return a default response if no triggers are matched
        return "I'm sorry, I didn't understand your question. Could you please rephrase it?";
    }

    // Handle "What should I eat to lose weight?" conversation trigger
    private function replyToLoseWeight()
    {
        $response = "To lose weight, you should focus on eating nutrient-dense foods that are low in calories and high in fiber. Here is a sample meal plan for a day:\n\n";
        $response .= "- Breakfast: oatmeal with berries and almonds\n";
        $response .= "- Snack: carrot sticks with hummus\n";
        $response .= "- Lunch: grilled chicken salad with mixed greens, cherry tomatoes, cucumber, and avocado\n";
        $response .= "- Snack: apple slices with peanut butter\n";
        $response .= "- Dinner: baked salmon with quinoa and steamed broccoli\n\n";
        $response .= "Remember to also stay hydrated and exercise regularly to help boost your weight loss efforts.";

        return $response;
    }

    // Handle "What should I eat to gain weight?" conversation trigger
    private function replyToGainWeight()
    {
        $response = "To gain weight, you should focus on eating nutrient-dense foods that are high in calories and protein. Here is a sample meal plan for a day:\n\n";
        $response .= "- Breakfast: whole grain toast with avocado and scrambled eggs\n";
        $response .= "- Snack: Greek yogurt with granola and mixed berries\n";
        $response .= "- Lunch: turkey and cheese sandwich on whole grain bread with a side of fruit salad\n";
        $response .= "- Snack: trail mix with nuts, seeds, and dried fruit\n";
        $response .= "- Dinner: grilled steak with sweet potato and steamed vegetables\n\n";
        $response .= "Remember to also engage in strength training exercises to help build muscle mass.";

        return $response;
    }
}