<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Process\Process;

use AppBundle\Entity\Gif;
use AppBundle\Entity\Version;

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

        /*
        $headers= array(
            'Content-Disposition' => 'inline; filename="'.basename($finalPath).'"',
            'Content-type'=>'image/gif',
            'Pragma'=>'no-cache',
            'Cache-Control'=>'no-cache'
        );
        $response = new Response($file, "200", $headers);
        */
        /*
        $response->setPrivate();
        $response->headers->set('Content-type', mime_content_type($finalPath));
        $response->headers->set('Content-length', filesize($finalPath));
        $response->sendHeaders();
        $response->setContent(readfile($finalPath));
        return $response;
        */

        /*
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $gif->getOriginalName()
        );
        return $response;
        */


        /*


        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        echo $process->getOutput();
        */
        // replace this example code with whatever you need
        /*
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
        ]);
        */
    }

}
