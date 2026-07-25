<?php
// ==========================================
// ItemController
// Handles the home page, posting lost/found items, searching, and
// viewing item details. Because ItemRepository already returns the
// correct LostItem/FoundItem object, this controller barely needs
// to know which type it's dealing with.
// ==========================================
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/ItemRepository.php';

class ItemController extends Controller
{
    private ItemRepository $items;

    public function __construct()
    {
        parent::__construct();
        $this->items = new ItemRepository();
        Auth::start();
    }

    public function home(): void
    {
        $recentLost = $this->items->getRecent('lost', 4);
        $recentFound = $this->items->getRecent('found', 4);
        $this->render('items/home', compact('recentLost', 'recentFound'));
    }

    public function showPostForm(): void
    {
        if (!Auth::check()) {
            $this->redirect('index.php?page=auth&action=showLogin');
            return;
        }

        $type = ($_GET['type'] ?? 'lost') === 'found' ? 'found' : 'lost';
        $this->render('items/post_item', ['errors' => [], 'old' => [], 'type' => $type]);
    }

    public function post(): void
    {
        if (!Auth::check()) {
            $this->redirect('index.php?page=auth&action=showLogin');
            return;
        }

        $type = ($_POST['type'] ?? 'lost') === 'found' ? 'found' : 'lost';

        $itemName = trim($_POST['item_name'] ?? '');
        $category = trim($_POST['category'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $location = trim($_POST['location'] ?? '');
        $itemDate = trim($_POST['item_date'] ?? '');
        $contactDetails = trim($_POST['contact_details'] ?? '');
        $errors = [];
        $imageName = null;

        if ($itemName === '') $errors[] = "Item name is required.";
        if ($category === '') $errors[] = "Please select a category.";
        if ($location === '') $errors[] = "Location is required.";
        if ($itemDate === '') $errors[] = "Date is required.";
        if ($contactDetails === '') $errors[] = "Contact details are required.";

        // ---- Optional image upload ----
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];

            if (!in_array($_FILES['image']['type'], $allowedTypes)) {
                $errors[] = "Only JPG or PNG images are allowed.";
            } elseif ($_FILES['image']['size'] > MAX_IMAGE_SIZE) {
                $errors[] = "Image size must be under 2MB.";
            } else {
                $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $imageName = $type . "_" . time() . "_" . uniqid() . "." . $extension;

                if (!move_uploaded_file($_FILES['image']['tmp_name'], UPLOAD_DIR . $imageName)) {
                    $errors[] = "Failed to upload image. Please try again.";
                    $imageName = null;
                }
            }
        }

        if (!empty($errors)) {
            $old = compact('itemName', 'category', 'description', 'location', 'itemDate', 'contactDetails');
            $this->render('items/post_item', ['errors' => $errors, 'old' => $old, 'type' => $type]);
            return;
        }

        $userId = Auth::user()->getId();
        $this->items->create($type, $userId, $itemName, $category, $description, $location, $itemDate, $imageName, $contactDetails);

        $this->redirect("index.php?page=item&action=search&type={$type}&posted=1");
    }

    public function search(): void
    {
        $type = ($_GET['type'] ?? 'lost') === 'found' ? 'found' : 'lost';
        $keyword = trim($_GET['keyword'] ?? '');
        $category = trim($_GET['category'] ?? '');
        $posted = isset($_GET['posted']);

        $results = $this->items->search($type, $keyword, $category);

        $this->render('items/search', compact('type', 'keyword', 'category', 'results', 'posted'));
    }

    public function view(): void
    {
        $type = ($_GET['type'] ?? 'lost') === 'found' ? 'found' : 'lost';
        $id = (int) ($_GET['id'] ?? 0);

        $item = $this->items->findById($type, $id);

        if (!$item) {
            echo "<div style='max-width:600px;margin:40px auto;font-family:Arial;text-align:center;'>Item not found.</div>";
            return;
        }

        $this->render('items/view_item', compact('item', 'type'));
    }
}
