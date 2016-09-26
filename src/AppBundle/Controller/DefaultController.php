<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Form\GifType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Process\Process;

use AppBundle\Entity\Gif;
use AppBundle\Entity\Version;

use GIFEndec\MemoryStream;
use GIFEndec\Decoder;
use GIFEndec\Frame;
use GIFEndec\Renderer;


class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
        ]);
    }


    /**
     * @Route("/gif", name="gif_index")
     * @Method("GET")
     */
    public function gifIndexAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT g
            FROM AppBundle:Gif g'
        );
        $gifs = $query->getResult();


        return $this->render('default/gif_index.html.twig', [
            'gifs' => $gifs
        ]);



    }


    /**
     * @Route("/gif/new", name="gif_new")
     * @Method("GET")
     */
    public function gifNewAction(Request $request)
    {
       // create a task and give it some dummy data for this example
        $gif = new Gif();

        $form = $this->createForm(GifType::class, $gif);

        return $this->render('default/new.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    /**
     * @Route("/gif", name="gif_create")
     * @Method("POST")
     */
    public function gifCreateAction(Request $request)
    {
        // just setup a fresh $task object (remove the dummy data)
        $gif = new Gif();

        $form = $this->createForm(GifType::class, $gif);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $gif->getSource();


            $hash = md5(uniqid());
            $gif->setOriginalName($file->getClientOriginalName());
            $gif->setHash($hash);

            $fileName = $hash . '.' . $file->guessExtension();


            $file->move(
                $this->get('kernel')->getRootDir() . '/../data/gifs',
                $fileName
            );
            // ... perform some action, such as saving the gif to the database
            // for example, if Task is a Doctrine entity, save it!
            $em = $this->getDoctrine()->getManager();
            $em->persist($gif);
            $em->flush();

            return $this->redirectToRoute('gif_new');
        }

        return $this->render('default/new.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    /**
     * @Route("/gif/{id}.gif", name="gif_show")
     * @ParamConverter("gif", class="AppBundle:Gif")
     */
    public function gifShowAction(Gif $gif)
    {


        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT v
            FROM AppBundle:Version v
            WHERE v.gifId = :gifId'
        )->setParameter('gifId', $gif->getId());
        $versions = $query->getResult();

        $rand = rand(0, count($versions) - 1);
        foreach($versions as $i => $v) {
            if ($rand == $i){
                $version = $v;
                break;
            }
        }

        $appRoot = $this->get('kernel')->getRootDir() . '/..';
        

        if (!$version->getHash()){
            $hash = md5(time());
            $finalPath = $appRoot . '/data/versions/' . $hash . '.gif';
            $version->setHash($hash);

            $query = $em->createQuery(
                'SELECT a
                FROM AppBundle:Ad a
                WHERE a.id = :adId'
            )->setParameter('adId', $version->getAdId());
            $ad = $query->getSingleResult();

            $adAbsolutePath = $appRoot . '/data/ads/' . $ad->getOriginalName();
            $gifAbsolutePath = $appRoot . '/data/gifs/' . $gif->getOriginalName();

            $cmd = "gifsicle --resize 300x200  -i \"${adAbsolutePath}\" > ad.gif";
            $process = new Process($cmd);
            $process->run();

            $cmd = "gifsicle --resize 300x200  -i \"${gifAbsolutePath}\" > gif.gif";
            $process = new Process($cmd);
            $process->run();

            $cmd = "gifsicle --colors 256 --merge \"ad.gif\" \"gif.gif\" -o \"${finalPath}\"";
            $process = new Process($cmd);
            $process->run();


            $em->persist($version);
            $em->flush();
        }

        $finalPath = $appRoot . '/data/versions/' . $version->getHash() . '.gif';            


        $image = $gif->getOriginalName();
        $file = file_get_contents($finalPath);
        $headers = array(
            'Content-Type'     => 'image/png',
            'Content-Disposition' => 'inline; filename="'.$image.'"');
        return new Response($file, 200, $headers);

 
    }


    /**
     * @Route("/gif/{id}/{noop}", name="gif_show_original")
     * @ParamConverter("gif", class="AppBundle:Gif")
     */
    public function gifShowOriginalAction(Gif $gif)
    {
        $appRoot = $this->get('kernel')->getRootDir() . '/..';

        $fileName = $appRoot . '/data/gifs/' . $gif->getHash() . '.gif';            

        $file = file_get_contents($fileName);
        $headers = array(
            'Content-Type'     => 'image/png',
            'Content-Disposition' => 'inline; filename="' . $gif->getOriginalName() . '"');

        return new Response($file, 200, $headers);
    }
}
