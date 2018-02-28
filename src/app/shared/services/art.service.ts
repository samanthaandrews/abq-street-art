import {HttpClient} from "@angular/common/http";
import {Injectable} from "@angular/core";
import {Observable} from "rxjs/Observable";
import {Art} from "../classes/art";

@Injectable()
export class UserService {

    constructor(protected http: HttpClient) {}

    // private userUrl = "https://jsonplaceholder.typicode.com/users/";

    getAllUsers() : Observable<Art[]> {
        return(this.http.get<Art[]>(this.artUrl));
    }
}