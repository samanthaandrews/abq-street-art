// Custom routes and fake URLs - this is what makes Angular cool
import {RouterModule, Routes} from "@angular/router";
import {HomeComponent} from "./home/home.component";
import {APP_BASE_HREF} from "@angular/common";
import {NavbarComponent} from "./shared/components/navbar.component";
import {ArtService} from "./shared/services/art.service";
import {AuthService} from "./shared/services/auth.service";
import {BookmarkService} from "./shared/services/bookmark.service";
import {CommentService} from "./shared/services/comment.service";
import {ProfileService} from "./shared/services/profile.service";
import {SignInService} from "./shared/services/sign.in.service";
import {SignUpService} from "./shared/services/sign.up.service";



// Every route you wish to express is a component
export const allAppComponents = [HomeComponent, NavbarComponent];

export const routes: Routes = [
	// Use our fake URLs - the browser will automatically swap in data.
	// The following is the default path - needs to come last in your array.
	// Sort your routes by most specific to least specific. Empty string "" matches everything.
    {path: "", component: HomeComponent}
];

const providers: any[] = [
   {provide:APP_BASE_HREF, useValue: window["_base_href"]}
	// Services is a way to connect to data: your own or external service (others' data). We only have one for this project. Typically one service per API. AJAX services.
   // UserService
];

const services: any[] = [ArtService, AuthService, BookmarkService, CommentService, ProfileService, SignInService, SignUpService];

export const appRoutingProviders : any[] = [providers,  services];

export const routing = RouterModule.forRoot(routes);

