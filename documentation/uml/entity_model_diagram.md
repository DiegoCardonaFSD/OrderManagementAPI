
@startuml

title Database Schema Diagram

!theme plain
hide circle
hide methods
hide stereotypes

skinparam linetype ortho

entity "admins" as admins {
  * id : BIGINT <<PK>>
  --
  name : VARCHAR
  email : VARCHAR (unique)
  password : VARCHAR
  created_at : TIMESTAMP
  updated_at : TIMESTAMP
}

entity "clients" as clients {
  * id : BIGINT <<PK>>
  --
  name : VARCHAR
  email : VARCHAR (unique)
  created_at : TIMESTAMP
  updated_at : TIMESTAMP
}

entity "users" as users {
  * id : BIGINT <<PK>>
  --
  client_id : BIGINT <<FK>>
  name : VARCHAR
  email : VARCHAR (unique)
  email_verified_at : TIMESTAMP
  password : VARCHAR
  remember_token : VARCHAR
  created_at : TIMESTAMP
  updated_at : TIMESTAMP
}

entity "orders" as orders {
  * id : BIGINT <<PK>>
  --
  client_id : BIGINT <<FK>>
  user_id : BIGINT <<FK>>
  status : VARCHAR
  total : DECIMAL(12,2)
  created_at : TIMESTAMP
  updated_at : TIMESTAMP
}

entity "order_items" as order_items {
  * id : BIGINT <<PK>>
  --
  order_id : BIGINT <<FK>>
  name : VARCHAR
  quantity : INT
  price : DECIMAL(12,2)
  subtotal : DECIMAL(12,2)
  created_at : TIMESTAMP
  updated_at : TIMESTAMP
}

entity "invoices" as invoices {
  * id : BIGINT <<PK>>
  --
  order_id : BIGINT <<FK>>
  status : VARCHAR
  message : TEXT
  created_at : TIMESTAMP
  updated_at : TIMESTAMP
}

' -------------------------
' Relaciones
' -------------------------

clients ||--o{ users : "1:N"
clients ||--o{ orders : "1:N"

users ||--o{ orders : "1:N"

orders ||--o{ order_items : "1:N"
orders ||--|| invoices : "1:1"

@enduml
