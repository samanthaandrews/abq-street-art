import {Injectable} from "@angular/core";
import {HttpClient, HttpParams} from "@angular/common/http";
import {Status} from "../classes/status";
import {Bookmark} from "../classes/bookmark";
import {Observable} from "rxjs/Observable";

@Injectable ()
export class BookmarkService {

	constructor(protected http: HttpClient) {

	}

	//define the API endpoint
	private bookmarkUrl = "/api/bookmark/";

	//call the bookmark API and create a new bookmark
	createBookmark(bookmark : Bookmark) : Observable<Status> {
		return (this.http.post<Status>(this.bookmarkUrl, bookmark));
	}

	//call to the bookmark API and delete (PUT) the bookmark in question
	//TODO I don't know if this delete (PUT) it written correctly at all. Should it be Status? Do I need all of these parameters? I thought we only used parameters when returning an array?
	deleteBookmark(bookmarkProfileId : string, bookmarkArtId : string) : Observable <Bookmark> {
		return (this.http.put<Bookmark>(this.bookmarkUrl, {params: new HttpParams().set("bookmarkProfileId", bookmarkProfileId).set("bookmarkArtId", bookmarkArtId)}));
	}

	//call the bookmark API and get by composite key
	getBookmarkByBookmarkArtIdAndBookmarkProfileId(bookmarkProfileId : string, bookmarkArtId : string) : Observable <Bookmark> {
		return (this.http.get<Bookmark>(this.bookmarkUrl, {params: new HttpParams().set("bookmarkProfileId", bookmarkProfileId).set("bookmarkArtId", bookmarkArtId)}));
	}

	//call the bookmark API and get by bookmark art id
	getBookmarkByBookmarkArtId(bookmarkArtId : string) : Observable <Bookmark[]> {
		return (this.http.get<Bookmark[]>(this.bookmarkUrl, {params: new HttpParams().set("bookmarkArtId", bookmarkArtId)}));
	}

	//call the bookmark API and get by bookmark profile id
	getBookmarkByBookmarkProfileId(bookmarkProfileId : string) : Observable <Bookmark[]> {
		return (this.http.get<Bookmark[]>(this.bookmarkUrl, {params: new HttpParams().set("bookmarkProfileId", bookmarkProfileId)}));
	}

}