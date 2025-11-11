@startuml OrderManagement

' Definición de tablas
entity "clients" as Clients {
  +id : bigint
  --
  name : string
  email : string
  status : enum(active,inactive)
  created_at : timestamp
  updated_at : timestamp
}

entity "admins" as Admins {
  +id : bigint
  --
  name : string
  email : string
  password : string
  created_at : timestamp
  updated_at : timestamp
}

entity "users" as Users {
  +id : bigint
  --
  client_id : bigint
  name : string
  email : string
  email_verified_at : timestamp
  password : string
  role : enum(admin,user)
  remember_token : string
  created_at : timestamp
  updated_at : timestamp
}

entity "orders" as Orders {
  +id : bigint
  --
  client_id : bigint
  user_id : bigint
  status : string
  total : decimal(12,2)
  customer_name : string
  customer_email : string
  customer_phone : string
  customer_address : text
  customer_city : string
  customer_country : string
  customer_tax_id : string
  notes : text
  created_at : timestamp
  updated_at : timestamp
}

entity "order_items" as OrderItems {
  +id : bigint
  --
  order_id : bigint
  name : string
  quantity : integer
  price : decimal(12,2)
  subtotal : decimal(12,2)
  created_at : timestamp
  updated_at : timestamp
}

entity "invoices" as Invoices {
  +id : bigint
  --
  order_id : bigint
  status : string
  message : text
  created_at : timestamp
  updated_at : timestamp
}

' Relaciones
Clients ||--o{ Users : "has many"
Clients ||--o{ Orders : "has many"
Users ||--o{ Orders : "has many"
Orders ||--o{ OrderItems : "has many"
Orders ||--o{ Invoices : "has many"

@enduml
