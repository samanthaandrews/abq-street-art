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
import {ArtComponent} from "./art/art.component";
import {SignInModalComponent} from "./shared/components/sign-in-modal.component";
import {SignUpComponent} from "./sign-up/sign-up.component";
import {UpdateProfileComponent} from "./update-profile/update-profile.component";
import {SignedInHomeviewComponent} from "./signed-in-homeview/signed-in-homeview.component";
import {SessionService} from "./shared/services/session.service";
import {CookieService} from "ngx-cookie-service";
import {DeepDiveInterceptor} from "./shared/interceptors/deep.dive.interceptor";
import {HTTP_INTERCEPTORS} from "@angular/common/http";


// Every route you wish to express is a component
export const allAppComponents = [HomeComponent, NavbarComponent, ArtComponent, SignInModalComponent, SignUpComponent, UpdateProfileComponent, SignedInHomeviewComponent];

export const routes: Routes = [
	// Use our fake URLs - the browser will automatically swap in data.
	// The following is the default path - needs to come last in your array.
	// Sort your routes by most specific to least specific. Empty string "" matches everything.
    {path: "sign-up", component: SignUpComponent},
    {path: "update-profile", component: UpdateProfileComponent},
    {path: "signed-in-homeview", component: SignedInHomeviewComponent},
    {path: "art", component: ArtComponent},
    {path: "", component: HomeComponent}

];


const providers: any[] = [
   {provide:APP_BASE_HREF, useValue: window["_base_href"]},
	{provide: HTTP_INTERCEPTORS, useClass: DeepDiveInterceptor, multi: true},
	// Services is a way to connect to data: your own or external service (others' data). We only have one for this project. Typically one service per API. AJAX services.
];

const services: any[] = [ArtService, AuthService, BookmarkService, CookieService,CommentService, ProfileService, SignInService, SignUpService, SessionService];

export const appRoutingProviders : any[] = [providers,  services];

export const routing = RouterModule.forRoot(routes);

