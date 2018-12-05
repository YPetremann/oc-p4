<?php
namespace Blog;
class Controller {
    public function __construct($as){
        $this->user = $as;
    }
    public function listPost(){
        global $view;
        try {
            $postManager = new PostManager($this->user);
            $posts = $postManager->list();
            $view->content = include "dat/view/BlogList.phtml";
        } catch(\Exception $e) {
            $view->message .= '<div class="error"><div class="fixer">'.$e->getMessage().'</div></div>';
            return false;
        }
    }
    public function createComment($id, $post) {
        global $view;
        try {
            $id = (int) $id;
            $comment = nl2br(htmlspecialchars((string) $post['comment'] ?: ""));

            if (empty($comment)) {
                throw new \Exception('Tous les champs ne sont pas remplis !');
            }

            $commentManager = new CommentManager($this->user);
            $affectedLines = $commentManager->create($id, $this->user->id, $comment);

            if (!$affectedLines) {
                return new \Exception("Impossible d'ajouter le commentaire !");
            } else {
                header('Location: '.$_SERVER['REQUEST_URI']);
            }
        } catch(\Exception $e) {
            $view->message .= '<div class="error"><div class="fixer">'.$e->getMessage().'</div></div>';
            return false;
        }
    }

        $commentManager = new CommentManager($_SESSION["user"]);
        $affectedLines = $commentManager->create($id, $author, $comment);

        if (!$affectedLines) {
            return new Exception("Impossible d'ajouter le commentaire !");
        } else {
            header('Location: '.$_SERVER['REQUEST_URI']);
        }
        return true;
    }
    public function readPost($id) {
        global $view;
        try {
            $id = (int) $id;
            $postManager = new PostManager($this->user);
            $post = $postManager->read($id);

            $commentManager = new CommentManager($this->user);
            $post['comments'] = $commentManager->list($id);

            $view->content = include "dat/view/BlogPost.phtml";
        } catch(\Exception $e) {
            $view->message .= '<div class="error"><div class="fixer">'.$e->getMessage().'</div></div>';
            return false;
        }
    }
}
