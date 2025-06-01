namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\ProductRepository; // <-- change here
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin_dashboard')]
    public function index(UserRepository $userRepository, ProductRepository $productRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $usersCount = $userRepository->count([]);
        $productsCount = $productRepository->count([]);

        return $this->render('admin/index.html.twig', [
            'usersCount' => $usersCount,
            'productsCount' => $productsCount,  // renamed
        ]);
    }

    #[Route('/admin/users', name: 'admin_manage_users')]
    public function manageUsers(UserRepository $userRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $users = $userRepository->findAll();

        return $this->render('admin/manage_users.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/admin/products', name: 'admin_manage_products')]  // changed route name
    public function manageProducts(ProductRepository $productRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $products = $productRepository->findAll();

        return $this->render('admin/manage_products.html.twig', [  // changed template name
            'products' => $products,
        ]);
    }
}
