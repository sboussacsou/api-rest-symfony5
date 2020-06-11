<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\Recette;
use App\Form\RecetteType;
use App\Repository\RecetteRepository;
/**
 * Movie controller.
 * @Route("/api", name="api_")
 */
class RecetteController extends AbstractFOSRestController
{
  /**
   * Lists all Recettes.
   * @Rest\Get("/recettes")
   *
   * @return Response
   */
  public function getRecetteAction()
  {
    $repository = $this->getDoctrine()->getRepository(Recette::class);
    $movies = $repository->findall();
    return $this->handleView($this->view($movies));
  }
  /**
   * Create recette.
   * @Rest\Post("/recette")
   *
   * @return Response
   */
  public function postRecetteAction(Request $request)
  {
    $movie = new Recette();
    $form = $this->createForm(RecetteType::class, $movie);
    $data = json_decode($request->getContent(), true);
    $form->submit($data);
    if ($form->isSubmitted() && $form->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->persist($movie);
      $em->flush();
      return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_CREATED));
    }
    return $this->handleView($this->view($form->getErrors()));
  }

    /**
   * Modify recette.
   * @Rest\Put("/recette/{id}")
   *
   * @return Response
   */
  public function deleteRecetteAction(Request $request, string $id)
  {
    $repo = $this->getDoctrine()->getRepository(Recette::class);
    $recette = $repo->find($id);
    $form = $this->createForm(RecetteType::class, $recette);
    $data = json_decode($request->getContent(), true);
    $form->submit($data);
    if ($form->isSubmitted() && $form->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->persist($recette);
      $em->flush();
      return $this->handleView($this->view(['status' => 'updated'], Response::HTTP_CREATED));
    }
    return $this->handleView($this->view($form->getErrors()));
  }
  /**
 * Delete recette.
 * @Rest\Delete("/recette/{id}")
 *
 * @return Response
 */
public function putRecetteAction(Request $request, string $id)
{
  $repo = $this->getDoctrine()->getRepository(Recette::class);
  $recette = $repo->find($id);
  if (is_object($recette)) {
    $em = $this->getDoctrine()->getManager();
    $em->remove($recette);
    $em->flush();
    return $this->handleView($this->view(['status' => 'deleted'], Response::HTTP_CREATED));
  }
  return $this->handleView($this->view($form->getErrors()));
}

}
