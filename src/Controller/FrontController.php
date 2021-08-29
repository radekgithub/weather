<?php

namespace App\Controller;

use App\Entity\Country;
use App\Entity\Temperature;
use App\Factory\TemperatureFactory;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    /**
     * @Route("/", name="front")
     */
    public function index(Request $request): Response
    {
        $data = null;
        $currentTemperature = null;
        $error = false;
        $form = $this->createFormBuilder()
            ->add('country', EntityType::class, [
                'class' => Country::class,
                'choice_label' => 'name',
                'placeholder' => 'Select country',
            ])
            ->add('city', TextType::class)
            ->add('submit', SubmitType::class, ['label' => 'Check temperature'])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $country = $data['country'];
            $city = $data['city'];
            if (null !== $currentTemperature = TemperatureFactory::checkTemperature($country, $city)) {
                $temperature = new Temperature();
                $temperature->setTemperature($currentTemperature);
                $temperature->setCountry($country);
                $temperature->setCity($city);
                $temperature->setCreated(new \DateTime());
                $em = $this->getDoctrine()->getManager();
                $em->persist($temperature);
                $em->flush();
            } else {
                $error = true;
            }
        }

        return $this->render('front/index.html.twig', [
            'form' => $form->createView(),
            'data' => $data,
            'temperature' => $currentTemperature,
            'error' => $error,
        ]);
    }
}
