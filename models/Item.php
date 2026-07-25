<?php
// ==========================================
// Abstract Item class
// LostItem and FoundItem both extend this. It holds every field the
// two have in common, and declares abstract methods that each
// subclass must answer differently - another polymorphism example:
// the same method name behaves differently depending on the object.
// ==========================================
abstract class Item
{
    private int $id;
    private int $userId;
    private string $itemName;
    private string $category;
    private string $description;
    private string $location;
    private string $itemDate;
    private ?string $image;
    private string $contactDetails;
    private string $status;
    private string $postedByName; // joined in from users table, read-only display data

    public function __construct(array $row)
    {
        $this->id = (int) $row['id'];
        $this->userId = (int) $row['user_id'];
        $this->itemName = $row['item_name'];
        $this->category = $row['category'];
        $this->description = $row['description'] ?? '';
        $this->location = $row['location'];
        $this->itemDate = $row['item_date'];
        $this->image = $row['image'] ?? null;
        $this->contactDetails = $row['contact_details'];
        $this->status = $row['status'];
        $this->postedByName = $row['full_name'] ?? '';
    }

    // ---- Getters ----
    public function getId(): int { return $this->id; }
    public function getUserId(): int { return $this->userId; }
    public function getItemName(): string { return $this->itemName; }
    public function getCategory(): string { return $this->category; }
    public function getDescription(): string { return $this->description; }
    public function getLocation(): string { return $this->location; }
    public function getItemDate(): string { return $this->itemDate; }
    public function getImage(): ?string { return $this->image; }
    public function getContactDetails(): string { return $this->contactDetails; }
    public function getStatus(): string { return $this->status; }
    public function getPostedByName(): string { return $this->postedByName; }

    // ---- Abstract: each subclass must define its own version ----
    abstract public function getTypeLabel(): string;      // "Lost" or "Found"
    abstract public function getBadgeClass(): string;      // CSS class for the badge
    abstract public function getDateFieldLabel(): string;  // "Date Lost" or "Date Found"
    abstract public function getTableName(): string;       // DB table this item lives in
}
