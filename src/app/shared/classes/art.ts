// TODO Do we need lat and long and image URL? WTF distance???
export class Art {
    constructor(public artId: string, public artAddress: string, public artArtist: string, public artImageUrl: string, public artLat: number, public artLocation: string, public artLong: number, public artTitle: string, public artType: string, public artYear: string) {}
}