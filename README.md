
# Mauritius Meetups

This is a fun weekend project which quickly became a not-so-weekend-project. it aggregates all the tech meetups happening around Mauritius to avoid clashes with organisers, and helps advertise the different meetups and events from the diverse user groups.


## Run Locally

Clone the project

```bash
  git clone https://github.com/xelab04/meetups.git
```

Go to the project directory

```bash
  cd meetups
  touch database/database.sqlite
```

For the Frontend stuff
```bash
npm i
npm run dev
```

For the PHP and Laravel stuff:
```bash
composer install
php artisan migrate:fresh --seed
php artisan key:generate
php artisan serve
```


## Deployment

This project comes with a convenient kubernetes folder with all the manifests you need for a deployment.

No, you do not need to deploy anywhere else. Only Kubernetes.


## Tech Stack

**Client:** TailwindCSS

**Server:** PHP, Laravel, FilamentPHP

**Infra:** Docker, Kubernetes


## Contributing

Contributions are more than welcome! Please feel free to raise issues or submit PRs. It's a lot of fun to have people contributing! Plus you get a special mention in the "acknowledgements" page (coming eventually)

### Pushing Your Contributions

Thank you for making it this far! If you would be so kind as to update the version number, that way my GH actions, and Kubernetes can have auto-deployment so your amazing contribution doesn't have to wait for me to be seen by the world!

You can easily change version by running `make minor` or `make major` depending on how big of a change it is. Kubernetes doesn't care which one has changed. Neither do I.

If you don't have Python, changing the version is in 2 steps.

    1. Update version in `version.txt`
    2. Update version in `kubernetes/app_deploy.yaml` on line 21
