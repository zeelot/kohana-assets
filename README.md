# 0.1.x
This release is purely for maintaining the old version of this module. I do not recommend using any `0.1.x` release but will fix minor bugs for anyone stuck using it. Kohana being an HMVC framework, supports isolated sub-requests and this branch adds a static class that is not properly isolated within each request. Version `1.0.0` will properly create instances of the `Assets` class for each view rendered. Follow that branch for more information on that development :)

I apologize if the badly thought-out structure of the `Assets` class in this branch has angered you in the past.

Cheers,


_Zeelot_