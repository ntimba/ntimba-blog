Table admin_social_links {
    link_id integer [pk, increment]
    name varchar(255) [not null]
    url varchar(255) [not null]
    icon_class varchar(255) [not null]
}

Table users {
    user_id integer [pk, increment]
    first_name varchar(255) [not null]
    last_name varchar(255) [not null]
    email varchar(255) [not null]
    username varchar(255) [null]
    password varchar(255) [not null]
    registration_date date [not null]
    role varchar(255) [not null]
    token varchar(255) [null]
    profile_picture varchar(255) [null]
    biography text [null]
    status bool 
    audited_account bool
}

Table posts {
    post_id integer [pk, increment]
    title varchar(255) [not null]
    slug varchar(255) [not null]
    content text [not null]
    publication_date datetime [not null]
    update_date datetime [null]
    featured_image_path varchar(255) [null]
    status varchar(255)
    category_id integer [ref: > post_categories.category_id ]
    user_id integer [ref: > users.user_id ]
}

Table comments {
    comment_id integer [pk, increment]
    content text [not null]
    date datetime [not null]
    post_id integer [ref: > posts.post_id]
    user_id integer [ref: > users.user_id]
    status bool
}

Table pages {
    page_id integer [pk, increment]
    title varchar(255) [not null]
    slug varchar(255) [not null]
    featured_image_path varchar(255) [null]
    status bool
    content text [not null]
    publication_date datetime [not null]
    update_date datetime [null]
    user_id integer [ref: > users.user_id]
}

Table post_categories {
    category_id integer [pk, increment]
    name varchar(255) [not null]
    slug varchar(255) [not null]
    description text [null]
    creation_date datetime [not null]
    parent_id integer [ref: - post_categories.category_id ]
}

Table settings {
    setting_id integer [pk, increment]
    blog_name varchar(255) [not null]
    blog_description text [null]
    logo_path varchar(255) [null]
    contact_email varchar(255) [not null]
    default_language varchar(255) [not null]
    timezone varchar(255) [not null]
    analytics_id varchar(255) [null]
    footer_text varchar(255) [null]
    maintenance_mode bool
}






